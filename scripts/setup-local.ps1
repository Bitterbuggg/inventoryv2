Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$projectName = Split-Path -Leaf $projectRoot
$envPath = Join-Path $projectRoot '.env'
$templateEnvPath = Join-Path $projectRoot 'env'

function Get-PhpExecutable {
    param(
        [string]$Root
    )

    $candidates = @(
        (Join-Path $Root '..\..\php\php.exe'),
        (Join-Path $Root '..\php\php.exe')
    )

    foreach ($candidate in $candidates) {
        try {
            $resolved = Resolve-Path -LiteralPath $candidate -ErrorAction Stop
            return $resolved.Path
        } catch {
        }
    }

    $phpCommand = Get-Command php -ErrorAction SilentlyContinue
    if ($phpCommand) {
        return $phpCommand.Source
    }

    throw 'PHP executable not found. Start with XAMPP or add php.exe to PATH.'
}

function Ensure-WritablePath {
    param(
        [string]$Path
    )

    New-Item -ItemType Directory -Path $Path -Force | Out-Null

    $indexPath = Join-Path $Path 'index.html'
    if (-not (Test-Path -LiteralPath $indexPath)) {
        New-Item -ItemType File -Path $indexPath | Out-Null
    }
}

function Get-EnvValue {
    param(
        [string]$Path,
        [string]$Key,
        [string]$DefaultValue
    )

    if (-not (Test-Path -LiteralPath $Path)) {
        return $DefaultValue
    }

    $escapedKey = [regex]::Escape($Key)
    $line = Get-Content -LiteralPath $Path | Where-Object { $_ -match "^\s*$escapedKey\s*=" } | Select-Object -Last 1
    if (-not $line) {
        return $DefaultValue
    }

    $value = ($line -split '=', 2)[1].Trim()
    return $value.Trim("'`"")
}

Set-Location $projectRoot

if (-not (Test-Path -LiteralPath $envPath)) {
    Copy-Item -LiteralPath $templateEnvPath -Destination $envPath
}

$writablePaths = @(
    (Join-Path $projectRoot 'writable\cache'),
    (Join-Path $projectRoot 'writable\logs'),
    (Join-Path $projectRoot 'writable\session'),
    (Join-Path $projectRoot 'writable\uploads'),
    (Join-Path $projectRoot 'writable\backups'),
    (Join-Path $projectRoot 'writable\debugbar')
)

foreach ($path in $writablePaths) {
    Ensure-WritablePath -Path $path
}

$phpExe = Get-PhpExecutable -Root $projectRoot

if (-not (Test-Path -LiteralPath (Join-Path $projectRoot 'vendor\autoload.php'))) {
    $composerCommand = Get-Command composer -ErrorAction SilentlyContinue
    if (-not $composerCommand) {
        throw 'Composer is required because vendor\autoload.php is missing.'
    }

    & $composerCommand.Source install
    if ($LASTEXITCODE -ne 0) {
        throw 'composer install failed.'
    }
}

$databaseName = Get-EnvValue -Path $envPath -Key 'database.default.database' -DefaultValue 'inventoryv2'

& $phpExe spark db:create $databaseName *> $null

& $phpExe spark migrate --all
if ($LASTEXITCODE -ne 0) {
    throw 'Database migration failed.'
}

& $phpExe spark db:seed AuthRbacSeeder
if ($LASTEXITCODE -ne 0) {
    throw 'AuthRbacSeeder failed.'
}

& $phpExe spark db:seed SampleCatalogSeeder
if ($LASTEXITCODE -ne 0) {
    throw 'SampleCatalogSeeder failed.'
}

& $phpExe spark db:seed SampleWorkflowSeeder
if ($LASTEXITCODE -ne 0) {
    throw 'SampleWorkflowSeeder failed.'
}

Write-Host ''
Write-Host 'Setup complete.'
Write-Host "Open: http://localhost/$projectName/"
