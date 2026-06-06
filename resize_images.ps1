Add-Type -AssemblyName System.Drawing

$dir = "c:\Users\osceola\Herd\scentify\public\images\hero-perfumes"
$images = Get-ChildItem -Path $dir -Filter *.png

foreach ($img in $images) {
    try {
        $bmp = [System.Drawing.Image]::FromFile($img.FullName)
        
        $newWidth = 600
        $newHeight = [math]::Round($bmp.Height * ($newWidth / $bmp.Width))
        
        $newBmp = New-Object System.Drawing.Bitmap($newWidth, $newHeight)
        $g = [System.Drawing.Graphics]::FromImage($newBmp)
        $g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
        $g.DrawImage($bmp, 0, 0, $newWidth, $newHeight)
        $g.Dispose()
        $bmp.Dispose()
        
        $newPath = $img.FullName -replace '\.png$', '.jpg'
        $newBmp.Save($newPath, [System.Drawing.Imaging.ImageFormat]::Jpeg)
        $newBmp.Dispose()
        
        Remove-Item $img.FullName
        Write-Host "Converted $($img.Name) to JPG and resized."
    } catch {
        Write-Host "Failed to process $($img.Name): $_"
    }
}
