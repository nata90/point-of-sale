; ==============================================================
; Script Inno Setup Compiler — Toko Cahaya Abadi POS
; Menghasilkan 1 Berkas Installer Mandiri: Setup_TokoCahayaAbadi_POS_v1.0.exe
; ==============================================================

#define MyAppName "Toko Cahaya Abadi POS"
#define MyAppVersion "1.0.0"
#define MyAppPublisher "Toko Cahaya Abadi"
#define MyAppExeName "launcher.vbs"

[Setup]
AppId={{9F82A45C-12A7-4B38-8FD1-B0C462D4A198}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}

; Lokasi default instalasi (User Local App Data agar database SQLite memiliki hak akses tulis penuh)
DefaultDirName={localappdata}\Programs\TokoCahayaAbadiPOS
DefaultGroupName={#MyAppName}
DisableProgramGroupPage=yes

; Direktori & nama output file installer
OutputDir=Output
OutputBaseFilename=Setup_TokoCahayaAbadi_POS_v1.0
SetupIconFile=app\web\favicon.ico

; Kompresi maksimal
Compression=lzma2/ultra64
SolidCompression=yes
WizardStyle=modern
PrivilegesRequired=lowest
ArchitecturesInstallIn64BitMode=x64
CloseApplications=yes

[Languages]
Name: "indonesian"; MessagesFile: "compiler:Languages\Indonesian.isl"
Name: "english"; MessagesFile: "compiler:Default.isl"

[Tasks]
Name: "desktopicon"; Description: "{cm:CreateDesktopIcon}"; GroupDescription: "{cm:AdditionalIcons}"

[Files]
; Skrip Peluncur Senyap & Eksekutor
Source: "launcher.vbs"; DestDir: "{app}"; Flags: ignoreversion
Source: "start.bat"; DestDir: "{app}"; Flags: ignoreversion
Source: "stop.bat"; DestDir: "{app}"; Flags: ignoreversion

; Folder PHP Portable Windows (termasuk php.ini dan folder ext)
Source: "php\*"; DestDir: "{app}\php"; Flags: ignoreversion recursesubdirs createallsubdirs

; Folder Source Code Aplikasi POS & Database SQLite
Source: "app\*"; DestDir: "{app}\app"; Flags: ignoreversion recursesubdirs createallsubdirs; Excludes: "*.git*,runtime\*,tests\*"

[Dirs]
; Pastikan folder data dan runtime dapat dibaca-tulis
Name: "{app}\app\data"; Permissions: users-full
Name: "{app}\app\runtime"; Permissions: users-full

[Icons]
; Shortcut di Start Menu
Name: "{group}\{#MyAppName}"; Filename: "{app}\{#MyAppExeName}"; WorkingDir: "{app}"; IconFilename: "{app}\app\web\favicon.ico"
Name: "{group}\Hentikan Server POS"; Filename: "{app}\stop.bat"; WorkingDir: "{app}"
Name: "{group}\{cm:UninstallProgram,{#MyAppName}}"; Filename: "{uninstallexe}"

; Shortcut di Desktop
Name: "{autodesktop}\{#MyAppName}"; Filename: "{app}\{#MyAppExeName}"; WorkingDir: "{app}"; Tasks: desktopicon; IconFilename: "{app}\app\web\favicon.ico"

[Run]
; Opsi jalankan aplikasi setelah instalasi selesai
Description: "{cm:LaunchProgram,{#StringChange(MyAppName, '&', '&&')}}"; Filename: "wscript.exe"; Parameters: """{app}\{#MyAppExeName}"""; Flags: nowait postinstall skipifsilent

[UninstallRun]
; Matikan proses PHP sebelum uninstall
Filename: "taskkill.exe"; Parameters: "/f /im php.exe"; Flags: runhidden
