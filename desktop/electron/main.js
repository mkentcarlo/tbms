const { app, BrowserWindow, Menu, Tray, shell } = require('electron');
const path = require('path');
const { spawn } = require('child_process');
const fs = require('fs');

let mainWindow;
let serverProcess;
let tray = null;
const SERVER_URL = 'http://127.0.0.1:8000';
const SERVER_CHECK_INTERVAL = 2000;

// Get the app directory (where electron app is located)
// When packaged, __dirname is the app.asar path, use process.resourcesPath
// When dev, __dirname is the actual folder
const isDev = !app.isPackaged;
const appDir = isDev ? __dirname : path.dirname(process.execPath);
const appRoot = isDev ? path.resolve(appDir, '..', '..') : path.dirname(appDir);

function createWindow() {
  const iconPath = path.join(__dirname, 'icon.ico');
  const windowOptions = {
    width: 1280,
    height: 800,
    minWidth: 1024,
    minHeight: 600,
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      webSecurity: true
    },
    show: false, // Don't show until server is ready
    title: 'TBMS - Treasury Budget Management System'
  };
  
  // Add icon if it exists
  if (fs.existsSync(iconPath)) {
    windowOptions.icon = iconPath;
  }
  
  mainWindow = new BrowserWindow(windowOptions);

  // Hide menu bar
  mainWindow.setMenuBarVisibility(false);

  // Check if server is running before loading
  checkServerAndLoad();

  mainWindow.on('closed', () => {
    mainWindow = null;
  });

  // Prevent navigation to external URLs
  mainWindow.webContents.on('will-navigate', (event, url) => {
    if (!url.startsWith(SERVER_URL)) {
      event.preventDefault();
      shell.openExternal(url);
    }
  });

  // Handle new window requests
  mainWindow.webContents.setWindowOpenHandler(({ url }) => {
    if (url.startsWith(SERVER_URL)) {
      return { action: 'allow' };
    } else {
      shell.openExternal(url);
      return { action: 'deny' };
    }
  });
}

function checkServerAndLoad() {
  const http = require('http');
  
  const checkServer = () => {
    const req = http.get(SERVER_URL, (res) => {
      if (res.statusCode === 200) {
        if (!mainWindow.isVisible()) {
          mainWindow.loadURL(SERVER_URL);
          mainWindow.show();
        }
      } else {
        setTimeout(checkServer, SERVER_CHECK_INTERVAL);
      }
    });

    req.on('error', () => {
      setTimeout(checkServer, SERVER_CHECK_INTERVAL);
    });

    req.setTimeout(1000, () => {
      req.destroy();
      setTimeout(checkServer, SERVER_CHECK_INTERVAL);
    });
  };

  checkServer();
}

function startServer() {
  // Find PHP executable
  let phpExe = 'php';
  const phpPath = path.join(appRoot, 'php', 'php.exe');
  if (fs.existsSync(phpPath)) {
    phpExe = phpPath;
  }

  // Change to app root directory
  const serverArgs = ['artisan', 'serve', '--host=127.0.0.1', '--port=8000'];
  
  console.log(`Starting server: ${phpExe} ${serverArgs.join(' ')}`);
  
  serverProcess = spawn(phpExe, serverArgs, {
    cwd: appRoot,
    stdio: 'ignore',
    detached: false
  });

  serverProcess.on('error', (err) => {
    console.error('Failed to start server:', err);
    if (mainWindow) {
      mainWindow.webContents.executeJavaScript(`
        document.body.innerHTML = '<div style="padding: 50px; text-align: center; font-family: Arial;">
          <h1>Server Error</h1>
          <p>Failed to start PHP server. Please ensure PHP is installed and in your PATH.</p>
          <p>Error: ${err.message}</p>
        </div>';
      `);
    }
  });

  serverProcess.on('exit', (code) => {
    console.log(`Server process exited with code ${code}`);
    if (code !== 0 && code !== null) {
      // Server crashed, try to restart
      setTimeout(startServer, 3000);
    }
  });
}

function createTray() {
  const iconPath = path.join(__dirname, 'icon.ico');
  // Use default icon if custom icon doesn't exist
  const trayIcon = fs.existsSync(iconPath) ? iconPath : undefined;
  tray = new Tray(trayIcon || path.join(__dirname, '..', '..', 'public', 'favicon.ico'));
  
  const contextMenu = Menu.buildFromTemplate([
    {
      label: 'Show Window',
      click: () => {
        if (mainWindow) {
          mainWindow.show();
        }
      }
    },
    {
      label: 'Hide Window',
      click: () => {
        if (mainWindow) {
          mainWindow.hide();
        }
      }
    },
    { type: 'separator' },
    {
      label: 'Quit',
      click: () => {
        app.quit();
      }
    }
  ]);

  tray.setToolTip('TBMS - Treasury Budget Management System');
  tray.setContextMenu(contextMenu);

  tray.on('click', () => {
    if (mainWindow) {
      if (mainWindow.isVisible()) {
        mainWindow.hide();
      } else {
        mainWindow.show();
      }
    }
  });
}

app.whenReady().then(() => {
  createTray();
  startServer();
  createWindow();

  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) {
      createWindow();
    }
  });
});

app.on('window-all-closed', () => {
  // Don't quit when all windows are closed (we have a tray icon)
  // app.quit();
});

app.on('before-quit', () => {
  if (serverProcess) {
    serverProcess.kill();
  }
});

// Handle app protocol (optional, for deep linking)
app.setAsDefaultProtocolClient('tbms');
