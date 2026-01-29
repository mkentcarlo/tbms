; TBMS Windows Installer - Inno Setup Script
; Build with: iscc installer\TBMS-Setup.iss
; Requires Inno Setup 6: https://jrsoftware.org/isinfo.php

#define MyAppName "TBMS"
#define MyAppVersion "1.0"
#define MyAppPublisher "TBMS"
#define MyAppURL "http://localhost:8000"
#define MyAppExeName "run-server.bat"
#define AppSource ".."

[Setup]
AppId={{A1B2C3D4-E5F6-7890-ABCD-EF1234567890}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}
AppPublisherURL={#MyAppURL}
AppSupportURL={#MyAppURL}
DefaultDirName=C:\TBMS
DefaultGroupName={#MyAppName}
AllowNoIcons=yes
OutputDir=Output
OutputBaseFilename=TBMS-Setup-{#MyAppVersion}
Compression=lzma2/ultra64
SolidCompression=yes
WizardStyle=modern
PrivilegesRequired=admin
ArchitecturesAllowed=x64compatible
ArchitecturesInstallIn64BitMode=x64compatible

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"

[Tasks]
Name: "startup"; Description: "Start TBMS when Windows starts"; GroupDescription: "Startup:"; Flags: unchecked
Name: "desktopicon"; Description: "Create a &desktop shortcut to open TBMS"; GroupDescription: "Shortcuts:"; Flags: unchecked

[Files]
; Laravel app - core (run from repo root; ensure vendor + public/build exist)
Source: "{#AppSource}\artisan"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\composer.json"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\composer.lock"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\server.php"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\package.json"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\package-lock.json"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\vite.config.js"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\tailwind.config.js"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\postcss.config.js"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\webpack.mix.js"; DestDir: "{app}"; Flags: ignoreversion
Source: "{#AppSource}\.env.xample"; DestDir: "{app}"; DestName: ".env.example"; Flags: ignoreversion
Source: "{#AppSource}\app\*"; DestDir: "{app}\app"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#AppSource}\bootstrap\*"; DestDir: "{app}\bootstrap"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#AppSource}\config\*"; DestDir: "{app}\config"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#AppSource}\database\*"; DestDir: "{app}\database"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "*.sqlite"
Source: "{#AppSource}\public\*"; DestDir: "{app}\public"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#AppSource}\resources\*"; DestDir: "{app}\resources"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#AppSource}\routes\*"; DestDir: "{app}\routes"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#AppSource}\vendor\*"; DestDir: "{app}\vendor"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#AppSource}\storage\*"; DestDir: "{app}\storage"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "*.key"
; Windows launcher scripts
Source: "run-server.bat"; DestDir: "{app}"; Flags: ignoreversion
Source: "install.bat"; DestDir: "{app}"; Flags: ignoreversion
Source: "install-autostart.bat"; DestDir: "{app}"; Flags: ignoreversion
Source: "uninstall-autostart.bat"; DestDir: "{app}"; Flags: ignoreversion
; Desktop app files
Source: "{#AppSource}\desktop\*"; DestDir: "{app}\desktop"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "*.log"

[Dirs]
Name: "{app}\storage\app\backups"; Permissions: users-full
Name: "{app}\storage\framework\cache"; Permissions: users-full
Name: "{app}\storage\framework\sessions"; Permissions: users-full
Name: "{app}\storage\framework\views"; Permissions: users-full
Name: "{app}\storage\logs"; Permissions: users-full
Name: "{app}\bootstrap\cache"; Permissions: users-full

[Icons]
Name: "{group}\TBMS Desktop"; Filename: "{app}\desktop\TBMS-Desktop.bat"; WorkingDir: "{app}"; IconFilename: "{sys}\shell32.dll"; IconIndex: 14
Name: "{group}\TBMS - Open in browser"; Filename: "http://localhost:8000"; IconFilename: "{sys}\shell32.dll"; IconIndex: 14
Name: "{group}\TBMS - Start server"; Filename: "{app}\run-server.bat"; WorkingDir: "{app}"
Name: "{group}\TBMS - Add to Windows startup"; Filename: "{app}\install-autostart.bat"; WorkingDir: "{app}"
Name: "{group}\TBMS - Remove from startup"; Filename: "{app}\uninstall-autostart.bat"; WorkingDir: "{app}"
Name: "{group}\Uninstall {#MyAppName}"; Filename: "{uninstallexe}"
Name: "{autodesktop}\TBMS Desktop"; Filename: "{app}\desktop\TBMS-Desktop.bat"; WorkingDir: "{app}"; IconFilename: "{sys}\shell32.dll"; IconIndex: 14; Tasks: desktopicon
Name: "{autodesktop}\TBMS - Open in browser"; Filename: "http://localhost:8000"; IconFilename: "{sys}\shell32.dll"; IconIndex: 14; Tasks: desktopicon

[Run]
Filename: "{app}\install.bat"; Description: "Run first-time setup (create .env, migrate database)"; Flags: postinstall nowait skipifsilent runascurrentuser
Filename: "{app}\install-autostart.bat"; Description: "Add TBMS to Windows startup"; Flags: postinstall nowait skipifsilent runascurrentuser; Tasks: startup

[UninstallRun]
Filename: "{app}\uninstall-autostart.bat"; Flags: runhidden waituntilterminated; RunOnceId: "RemoveAutostart"
