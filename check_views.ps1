
$viewsDir = "resources\views"
$searchDirs = @("app", "resources\views", "routes")

# Get all blade files
$viewFiles = Get-ChildItem -Path $viewsDir -Filter *.blade.php -Recurse

# Create a list of view names in dot notation
$views = @()
foreach ($file in $viewFiles) {
    $relPath = Resolve-Path -Path $file.FullName -RelativeBase $viewsDir
    $viewName = $relPath.Replace(".blade.php", "").Replace("\", ".").Replace("/", ".")
    if ($viewName.StartsWith(".")) { $viewName = $viewName.Substring(1) }
    $views += [PSCustomObject]@{
        Name = $viewName
        Path = $file.FullName
    }
}

# Search for each view name in the codebase
$unused = @()
foreach ($view in $views) {
    $found = $false
    $name = $view.Name
    
    # Simple search for 'name' or "name"
    $searchStrings = @("'$name'", "`"$name`"")
    
    foreach ($dir in $searchDirs) {
        if (Test-Path $dir) {
            # Use Select-String for fast searching
            if (Select-String -Path "$dir\*" -Pattern $name -Quiet -Recurse -Exclude "*.blade.php.old", "*.bak") {
                $found = $true
                break
            }
        }
    }
    
    if (-not $found) {
        $unused += $view.Name
    }
}

Write-Host "Total views: $($views.Count)"
Write-Host "Potentially unused views: $($unused.Count)"
foreach ($v in $unused) {
    Write-Host $v
}
