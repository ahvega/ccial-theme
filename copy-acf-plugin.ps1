# PowerShell script to copy Advanced Custom Fields Pro plugin files
$source = 'D:\WebDev\Source\CCIAL\Websites\ccial.org\public_html\wp-content\plugins\advanced-custom-fields-pro\*'
$destination = 'D:\WebDev\Source\CCIAL\Websites\quebelleza.ccial.org\public_html\wp-content\plugins\advanced-custom-fields-pro'
$ftpScript = 'D:\WebDev\Source\CCIAL\Websites\ccial.org\public_html\acf_ftp_download.txt'

# Download files from FTP server
Write-Host "Starting FTP download using $ftpScript..."
ftp -s:$ftpScript
Write-Host "FTP download completed."

# Copy files locally
Copy-Item -Path $source -Destination $destination -Recurse -Force

Write-Host "Files copied from $source to $destination successfully."