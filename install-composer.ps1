$expectedChecksum = Get-Content -Path "installer.sig"
Copy-Item -Path "installer" -Destination "composer-setup.php"

$actualChecksum = Get-FileHash composer-setup.php -Algorithm sha384 | Select-Object -ExpandProperty Hash

if ($expectedChecksum -ne $actualChecksum) {
    Write-Host 'ERROR: Invalid installer checksum' -ForegroundColor Red
    Remove-Item composer-setup.php
    Exit 1
}

php composer-setup.php --quiet
$result = $LASTEXITCODE
Remove-Item composer-setup.php
Exit $result
