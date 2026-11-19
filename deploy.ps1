# ============================================================
# DEPLOY SCRIPT - Portal Siantar Barat
# GitHub Push + ZIP Export (Secure & Modern)
# ============================================================
# Usage:
#   .\deploy.ps1                        -> Git push + ZIP
#   .\deploy.ps1 -Mode git              -> Git push only
#   .\deploy.ps1 -Mode zip              -> ZIP only
#   .\deploy.ps1 -Mode git -Msg "text"  -> Git push with custom message
# ============================================================

param(
    [ValidateSet("all","git","zip")]
    [string]$Mode = "all",

    [string]$Msg = "",

    [string]$Branch = "main",

    [string]$Remote = "origin",

    [string]$RepoUrl = "https://github.com/boimanalu/sipakar.git",

    [switch]$DryRun
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

# ── Color Helpers ─────────────────────────────────────────
function Write-Header($t)  { Write-Host "`n$('='*55)" -ForegroundColor DarkCyan; Write-Host "  $t" -ForegroundColor Cyan; Write-Host $('='*55) -ForegroundColor DarkCyan }
function Write-Step($t)    { Write-Host "  → $t" -ForegroundColor White }
function Write-Ok($t)      { Write-Host "  ✓ $t" -ForegroundColor Green }
function Write-Warn($t)    { Write-Host "  ⚠ $t" -ForegroundColor Yellow }
function Write-Fail($t)    { Write-Host "  ✗ $t" -ForegroundColor Red; throw $t }
function Write-Info($t)    { Write-Host "    $t" -ForegroundColor DarkGray }

# ── Project Paths ─────────────────────────────────────────
$ProjectRoot = "C:\laragon\www\siantarbarat"
$ZipDir      = "C:\laragon\www"
$ZipName     = "siantarbarat_$(Get-Date -Format 'yyyyMMdd_HHmmss').zip"
$ZipPath     = Join-Path $ZipDir $ZipName

# ── Excluded from ZIP & Git ────────────────────────────────
# Files/folders that must NEVER be in a repository or archive:
$ExcludeFromZip = @(
    "vendor",          # composer packages (reinstall with: composer install)
    "writable/logs",   # server log files
    "writable/cache",  # cache files
    "writable/session",# PHP sessions
    "writable/uploads",# user uploads (too large / sensitive)
    "writable/debugbar",
    ".git",
    ".vscode",
    "scratch",
    "database/backups",
    "_ci4_staging",
    "node_modules",
    "*.sql",           # raw SQL dumps (may contain sensitive data)
    "*.sql.gz"
)

# ── .gitignore content ────────────────────────────────────
$GitIgnoreContent = @"
# ==============================================================
# .gitignore — Portal Siantar Barat (CI4)
# JANGAN commit file berikut ke repository
# ==============================================================

# 1. Kredensial & Environment — WAJIB DIEXCLUDE
.env
.env.*
!.env.example
*.pem
*.key
*.cert

# 2. Dependency — Di-install ulang dengan `composer install`
/vendor/

# 3. File sistem & cache CI4
/writable/cache/*
/writable/logs/*
/writable/session/*
/writable/uploads/*
/writable/debugbar/*
/writable/backups/*
!writable/cache/.gitkeep
!writable/logs/.gitkeep
!writable/session/.gitkeep
!writable/uploads/.gitkeep

# 4. Database dump (berisi data warga — SENSITIF)
*.sql
*.sql.gz
database/backups/

# 5. Editor & OS
.vscode/
.idea/
.DS_Store
Thumbs.db
desktop.ini

# 6. PHPUnit & Test artifacts
.phpunit.cache/
coverage/

# 7. Script development lokal
scratch/
_ci4_staging/
import_*.php
db_import.php
setup_local.php
*.bat
*.ps1
!deploy.ps1

# 8. File log & temp
*.log
*.tmp
*.bak
*.swp

# 9. Composer lock (opsional — boleh include untuk reproducibility)
# composer.lock

# 10. Gambar besar aset galeri (opsional, aktifkan jika >100MB)
# asset/files/
# asset/images/
"@

# ── .gitattributes content ────────────────────────────────
$GitAttributesContent = @"
# Normalize line endings
* text=auto

# PHP files always use LF
*.php text eol=lf
*.html text eol=lf
*.css text eol=lf
*.js text eol=lf
*.json text eol=lf
*.md text eol=lf
*.xml text eol=lf
*.sql text eol=lf

# Binary files — no diff/merge
*.png binary
*.jpg binary
*.jpeg binary
*.gif binary
*.ico binary
*.zip binary
*.pdf binary
*.ttf binary
*.woff binary
*.woff2 binary
"@

# ── FUNCTION: Ensure .gitkeep in writable subdirs ─────────
function Ensure-GitKeep {
    $dirs = @(
        "$ProjectRoot\writable\cache",
        "$ProjectRoot\writable\logs",
        "$ProjectRoot\writable\session",
        "$ProjectRoot\writable\uploads"
    )
    foreach ($d in $dirs) {
        if (-not (Test-Path $d)) { New-Item -ItemType Directory -Path $d -Force | Out-Null }
        $gk = Join-Path $d ".gitkeep"
        if (-not (Test-Path $gk)) { New-Item -ItemType File -Path $gk -Force | Out-Null }
    }
    Write-Ok "writable/ .gitkeep ensured"
}

# ── FUNCTION: Security Scan ────────────────────────────────
function Invoke-SecurityScan {
    Write-Step "Menjalankan security scan file..."

    $patterns = @(
        "sk-[a-zA-Z0-9]{40,}",             # OpenAI API key
        "AKIA[0-9A-Z]{16}",                # AWS Access Key
        "password\s*=\s*['""][^'""]{4,}",  # Hardcoded password
        "secret\s*=\s*['""][^'""]{8,}",    # Hardcoded secret
        "token\s*=\s*['""][^'""]{20,}"     # Long token
    )

    $scanDirs = @("$ProjectRoot\app", "$ProjectRoot\template")
    $findings = @()

    foreach ($dir in $scanDirs) {
        if (Test-Path $dir) {
            $files = Get-ChildItem $dir -Recurse -Include "*.php","*.js","*.json","*.env" -File
            foreach ($f in $files) {
                $content = Get-Content $f.FullName -Raw -ErrorAction SilentlyContinue
                if ($null -eq $content) { continue }
                foreach ($pat in $patterns) {
                    if ($content -match $pat) {
                        $findings += "  ⚠ Potensi kredensial di: $($f.FullName)"
                    }
                }
            }
        }
    }

    if ($findings.Count -gt 0) {
        Write-Warn "Security scan menemukan potensi masalah:"
        $findings | ForEach-Object { Write-Host $_ -ForegroundColor Yellow }
        $ans = Read-Host "  Lanjutkan deploy? (y/N)"
        if ($ans -ne "y") { Write-Fail "Deploy dibatalkan oleh pengguna (security concern)." }
    } else {
        Write-Ok "Security scan bersih — tidak ada credential exposure terdeteksi"
    }
}

# ── FUNCTION: PHP Syntax Check ────────────────────────────
function Invoke-PhpSyntaxCheck {
    Write-Step "Memeriksa syntax PHP..."
    $phpExe = "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"
    if (-not (Test-Path $phpExe)) {
        Write-Warn "PHP tidak ditemukan di $phpExe — lewati syntax check"
        return
    }

    $phpFiles = Get-ChildItem "$ProjectRoot\app" -Recurse -Include "*.php" -File
    $errors   = @()
    foreach ($f in $phpFiles) {
        $result = & $phpExe -l $f.FullName 2>&1
        if ($LASTEXITCODE -ne 0) {
            $errors += $f.FullName
        }
    }

    if ($errors.Count -gt 0) {
        Write-Warn "Ditemukan $($errors.Count) file PHP dengan error:"
        $errors | ForEach-Object { Write-Host "  ✗ $_" -ForegroundColor Red }
        $ans = Read-Host "  Lanjutkan deploy? (y/N)"
        if ($ans -ne "y") { Write-Fail "Deploy dibatalkan — ada error PHP." }
    } else {
        Write-Ok "Semua file PHP valid ($($phpFiles.Count) file diperiksa)"
    }
}

# ── FUNCTION: Setup Git ───────────────────────────────────
function Setup-Git {
    Set-Location $ProjectRoot

    # Write .gitignore
    $giPath = Join-Path $ProjectRoot ".gitignore"
    Set-Content -Path $giPath -Value $GitIgnoreContent -Encoding UTF8
    Write-Ok ".gitignore ditulis"

    # Write .gitattributes
    $gaPath = Join-Path $ProjectRoot ".gitattributes"
    Set-Content -Path $gaPath -Value $GitAttributesContent -Encoding UTF8
    Write-Ok ".gitattributes ditulis"

    # Ensure writable .gitkeep
    Ensure-GitKeep

    # Init repo if not exists
    if (-not (Test-Path (Join-Path $ProjectRoot ".git"))) {
        Write-Step "Inisialisasi git repository baru..."
        git init -b main
        Write-Ok "Git repo diinit (branch: main)"
    } else {
        Write-Ok "Git repo sudah ada"
    }

    # Set author identity
    $gitName  = git config user.name 2>&1
    $gitEmail = git config user.email 2>&1
    if ([string]::IsNullOrWhiteSpace($gitName)) {
        git config user.name  "boimanalu"
        git config user.email "boimanalu@users.noreply.github.com"
        Write-Ok "Git identity dikonfigurasi"
    }

    # Set remote
    $remotes = git remote 2>&1
    if ($remotes -notcontains $Remote) {
        git remote add $Remote $RepoUrl
        Write-Ok "Remote '$Remote' ditambahkan: $RepoUrl"
    } else {
        $existingUrl = git remote get-url $Remote 2>&1
        if ($existingUrl -ne $RepoUrl) {
            git remote set-url $Remote $RepoUrl
            Write-Ok "Remote '$Remote' URL diperbarui: $RepoUrl"
        } else {
            Write-Ok "Remote '$Remote' sudah benar"
        }
    }
}

# ── FUNCTION: Git Push ────────────────────────────────────
function Invoke-GitPush {
    Set-Location $ProjectRoot

    # Stage all
    Write-Step "git add -A ..."
    if (-not $DryRun) {
        git add -A
        Write-Ok "Files staged"
    }

    # Build commit message
    if ([string]::IsNullOrWhiteSpace($Msg)) {
        $timestamp  = Get-Date -Format "yyyy-MM-dd HH:mm"
        $Msg = "feat: update portal Siantar Barat - $timestamp"
    }

    # Check if there's anything to commit
    $status = git status --porcelain 2>&1
    if ([string]::IsNullOrWhiteSpace($status)) {
        Write-Warn "Tidak ada perubahan baru untuk di-commit"
        return
    }

    Write-Step "git commit: `"$Msg`""
    if (-not $DryRun) {
        git commit -m $Msg
        Write-Ok "Commit berhasil"
    }

    # Push
    Write-Step "git push $Remote $Branch ..."
    if (-not $DryRun) {
        git push $Remote $Branch 2>&1 | Tee-Object -Variable pushOutput
        if ($LASTEXITCODE -eq 0) {
            Write-Ok "Push ke GitHub berhasil!"
            Write-Info "Branch: $Branch | Remote: $RepoUrl"
        } else {
            Write-Warn "Push gagal. Coba dengan --force atau periksa autentikasi."
            Write-Info "Jika pertama kali: git push -u $Remote $Branch"
            Write-Info "Output: $pushOutput"
        }
    } else {
        Write-Ok "[DRY RUN] Commit + Push disimulasikan"
    }
}

# ── FUNCTION: Build ZIP ───────────────────────────────────
function Invoke-BuildZip {
    Write-Step "Membangun file ZIP deployment..."

    if (-not (Test-Path $ZipDir)) {
        New-Item -ItemType Directory -Path $ZipDir -Force | Out-Null
    }

    # Collect files to zip (exclude sensitive/large)
    $allFiles = Get-ChildItem $ProjectRoot -Recurse -File
    $included = @()

    foreach ($file in $allFiles) {
        $rel = $file.FullName.Substring($ProjectRoot.Length + 1)
        $skip = $false

        foreach ($ex in $ExcludeFromZip) {
            # Pattern matching (folder prefix OR wildcard extension)
            if ($ex.StartsWith("*")) {
                if ($rel -like $ex) { $skip = $true; break }
            } else {
                if ($rel -like "$ex\*" -or $rel -like "$ex/*" -or $rel.StartsWith($ex)) {
                    $skip = $true; break
                }
            }
        }

        # Always exclude .env
        if ($rel -eq ".env" -or $rel -like "*.env.local") { $skip = $true }

        if (-not $skip) { $included += $file }
    }

    Write-Info "File yang akan dizip: $($included.Count)"

    if (-not $DryRun) {
        # Remove existing zip if same name
        if (Test-Path $ZipPath) { Remove-Item $ZipPath -Force }

        # Build ZIP using .NET for reliability
        Add-Type -AssemblyName System.IO.Compression.FileSystem
        $zip = [System.IO.Compression.ZipFile]::Open($ZipPath, 'Create')

        foreach ($file in $included) {
            $rel = $file.FullName.Substring($ProjectRoot.Length + 1)
            $entry = $zip.CreateEntry($rel, [System.IO.Compression.CompressionLevel]::Optimal)
            $entryStream = $entry.Open()
            $fileStream  = [System.IO.File]::OpenRead($file.FullName)
            $fileStream.CopyTo($entryStream)
            $fileStream.Close()
            $entryStream.Close()
        }

        $zip.Dispose()

        $zipSize = [math]::Round((Get-Item $ZipPath).Length / 1MB, 2)
        Write-Ok "ZIP dibuat: $ZipPath ($zipSize MB)"
        Write-Info "Total files: $($included.Count)"
    } else {
        Write-Ok "[DRY RUN] ZIP akan dibuat di: $ZipPath"
        Write-Info "Total files: $($included.Count)"
    }
}

# ============================================================
# MAIN EXECUTION
# ============================================================
Write-Header "🚀 DEPLOY - Portal Siantar Barat"
Write-Info "Mode    : $Mode"
Write-Info "Branch  : $Branch"
Write-Info "Remote  : $RepoUrl"
Write-Info "DryRun  : $DryRun"

try {
    # 1. Security scan (selalu)
    if (-not $DryRun) {
        Invoke-SecurityScan
    }

    # 2. PHP syntax check
    Invoke-PhpSyntaxCheck

    # 3. Git operations
    if ($Mode -eq "all" -or $Mode -eq "git") {
        Write-Header "📦 GIT PUSH"
        Setup-Git
        Invoke-GitPush
    }

    # 4. ZIP export
    if ($Mode -eq "all" -or $Mode -eq "zip") {
        Write-Header "🗜  BUILD ZIP"
        Invoke-BuildZip
    }

    Write-Header "✅ SELESAI"
    Write-Ok "Deploy berhasil pada $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
    if ($Mode -eq "all" -or $Mode -eq "zip") {
        Write-Info "ZIP: $ZipPath"
    }
    if ($Mode -eq "all" -or $Mode -eq "git") {
        Write-Info "Repo: $RepoUrl (branch: $Branch)"
    }

} catch {
    Write-Host "`n  ✗ DEPLOY GAGAL: $_" -ForegroundColor Red
    exit 1
}
