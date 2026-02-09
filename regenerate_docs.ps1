$outputFile = "proiektu_kodea.md"
$content = @"
# Proiektuaren Kodea

Hemen proiektuaren PHP, CSS, JS eta SQL fitxategi guztiak ikus ditzakezu.

---

"@

# PHP fitxategiak
$content += "`n## PHP Fitxategiak`n"
$phpFiles = Get-ChildItem -Path "php" -Filter "*.php"
foreach ($file in $phpFiles) {
    $content += "`n### php/$($file.Name)`n"
    $content += "````php`n"
    $content += Get-Content -Path $file.FullName -Raw
    $content += "`n````n"
}

# CSS fitxategiak
$content += "`n## CSS Fitxategiak`n"
$cssFiles = Get-ChildItem -Path "css" -Filter "*.css"
foreach ($file in $cssFiles) {
    $content += "`n### css/$($file.Name)`n"
    $content += "````css`n"
    $content += Get-Content -Path $file.FullName -Raw
    $content += "`n````n"
}

# JS fitxategiak
$content += "`n## JS Fitxategiak`n"
$jsFiles = Get-ChildItem -Path "js" -Filter "*.js"
foreach ($file in $jsFiles) {
    $content += "`n### js/$($file.Name)`n"
    $content += "````javascript`n"
    $content += Get-Content -Path $file.FullName -Raw
    $content += "`n````n"
}

# SQL fitxategiak
$content += "`n## SQL Fitxategiak`n"
$sqlFiles = Get-ChildItem -Path "sql" -Filter "*.sql"
foreach ($file in $sqlFiles) {
    $content += "`n### sql/$($file.Name)`n"
    $content += "````sql`n"
    $content += Get-Content -Path $file.FullName -Raw
    $content += "`n````n"
}

Set-Content -Path $outputFile -Value $content -Encoding utf8
