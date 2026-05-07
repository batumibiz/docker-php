# php-apache-ssl

## Local Development with Docker

> [!IMPORTANT]
>
> **For Windows Users:**
> It is **strongly recommended** to use the WSL 2 (Windows Subsystem for Linux) backend for Docker Desktop.
> **You should clone this project _inside_ your WSL distribution (e.g., Ubuntu 24.04) for best performance.**
> Access your project via `\\wsl$\Ubuntu-24.04\home\youruser\opencart` from Windows Explorer if needed.
> Without WSL 2, file system performance will be extremely slow, making the application nearly unusable.
> Docker Desktop will typically prompt you to enable WSL 2 during installation.

> [!IMPORTANT]
>
> **Environment Compatibility**  
> The main Docker container is based on Alpine Linux.
> Please note that GLOB_BRACE is not supported in the Alpine/musl environment.
> Avoid using this flag in your project or use Debian based environments instead.

> [!NOTE]
>
> - The `make` commands are only available on Linux / macOS / Windows WSL 2.
> - To use `make db-dump` or `make db-restore`, you must have `mariadb-client` or `mysql-client` installed.

### Using Make
- Start all services
    ```bash
    make up
    ```
- Stop all services
    ```bash
    make down
    ```
- Dump database: the dump will be saved to `.docker/db-import/dump.sql`
    ```bash
    make db-dump
    ```
- Restore a saved database dump
    ```bash
    make db-restore
    ```

### Or using Docker CLI
- Start all services
    ```bash
    docker compose up -d
    ```

### Docker Environment

The project environment will be available at the following addresses:
- **WEB**:
  - HTTP: http://localhost
  - HTTPS (SSL): https://localhost
- **Mailpit** (Email Testing): http://localhost:8025
- MySQL (MariaDB):
  - Host: `mysql`
  - Username: `root`
  - Password: `root`
  - Database: `database`
- **Logs**: all service logs (Web server, PHP, etc.) are accessible in the `.docker/log` directory for easy debugging.

### SSL Configuration
To prevent browser connection errors, please choose one of the following options:
- **Install CA Certificate**: Import the CA certificate located at `.docker/web/ssl/ca.crt` into your operating system's trusted store.
- **Or use Custom Certificates**: Replace the existing `.docker/web/ssl/localhost.crt` and `.docker/web/ssl/localhost.key` with your own generated certificates for `localhost`.

### Profiling with XDebug
You can perform detailed profiling using XDebug to analyze the performance of all subsystems and identify bottlenecks. To enable this, follow these steps:

1. **Enable XDebug Settings**: uncomment lines 2-6 in the configuration file `.docker/web/config/xdebug.ini`.
2. **Rebuild Container**: run the following command:
   ```bash
   make down && make build && make up
   ```
3. **Browser Extension**: you will need a browser extension to trigger profiling (e.g., [Xdebug Helper](https://chromewebstore.google.com/detail/xdebug-helper-by-jetbrain/aoelhdemabeimdhedkidlnbkfhnhgnhm) or similar).
4. **Log Location**: detailed profiling logs will be saved in the `.docker/log/xdebug` folder
5. **Analysis Tools**: To analyze the generated logs, use specialized software such as:  
   [PhpStorm](https://www.jetbrains.com/phpstorm/), [PHP Profiler for VS Code](https://marketplace.visualstudio.com/items?itemName=DEVSENSE.profiler-php-vscode), [KCachegrind](https://kcachegrind.github.io/html/Home.html), [WinCacheGrind](https://sourceforge.net/projects/wincachegrind/), or similar tools.

#### Step-by-Step Example
Assuming the preparation steps above are completed...
1. Open the desired page in your browser (e.g., Homepage: https://localhost).
2. Refresh the page 2–3 times to "warm up" the cache.
3. In your **Xdebug Helper** extension, select the "**Profile**" mode.
4. **Profiling**: refresh the page **only once**.
5. In the extension, switch back to "**Disable**".
6. The detailed profiling log will be available at:  
   `.docker/log/xdebug/cachegrind.out.XX.gz`

You can now open this file in your preferred analysis tool to inspect the performance data.