# Ensure MySQL is running on port 3306 before launching the application
$port = 3306
$maxAttempts = 30
$ready = $false

for ($i = 0; $i -lt $maxAttempts; $i++) {
    try {
        $tcp = [System.Net.Sockets.TcpClient]::new()
        $tcp.Connect('127.0.0.1', $port)
        $tcp.Close()
        $ready = $true
        break
    } catch {
        if ($i -eq 0) {
            Write-Host "      MySQL is not running on port $port. Starting MySQL..." -ForegroundColor Yellow
            $mysqlExe = 'C:\xampp\mysql\bin\mysqld.exe'
            $mysqlIni = 'C:\xampp\mysql\bin\my.ini'
            
            if (Test-Path $mysqlExe) {
                # Start detached process so it doesn't terminate when console window is closed
                Invoke-CimMethod -ClassName Win32_Process -MethodName Create -Arguments @{
                    CommandLine = "`"$mysqlExe`" --defaults-file=`"$mysqlIni`" --standalone"
                    CurrentDirectory = 'C:\xampp\mysql'
                } | Out-Null
            } else {
                Write-Host "      ERROR: Could not find MySQL at $mysqlExe" -ForegroundColor Red
                exit 1
            }
        }
        Start-Sleep -Milliseconds 500
    }
}

if ($ready) {
    Write-Host "      MySQL is connected and ready." -ForegroundColor Green
    exit 0
} else {
    Write-Host "      ERROR: MySQL failed to respond on port $port within 15 seconds." -ForegroundColor Red
    exit 1
}
