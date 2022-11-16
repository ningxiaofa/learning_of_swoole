<?php

putenv('FOO=BAR');
var_dump(getenv('FOO')); // string(3) "BAR"

print_r($_ENV);
// Array
// (
// )

print_r($_SERVER);
// Array
// (
//     [SECURITYSESSIONID] => 186a5
//     [USER] => huangbaoyin
//     [MallocNanoZone] => 0
//     [__CFBundleIdentifier] => com.microsoft.VSCode
//     [COMMAND_MODE] => unix2003
//     [PATH] => /opt/homebrew/bin:/opt/homebrew/sbin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:/opt/homebrew/bin:/opt/homebrew/sbin
//     [SHELL] => /bin/zsh
//     [HOME] => /Users/huangbaoyin
//     [__CF_USER_TEXT_ENCODING] => 0x1F5:0x19:0x34
//     [LaunchInstanceID] => FC0B49C7-E05E-4BD9-A538-B19A95138A42
//     [XPC_SERVICE_NAME] => 0
//     [SSH_AUTH_SOCK] => /private/tmp/com.apple.launchd.ktAmoSdb8C/Listeners
//     [XPC_FLAGS] => 0x0
//     [LOGNAME] => huangbaoyin
//     [TMPDIR] => /var/folders/gq/ctrfb6md4yz15qhl5v726k980000gn/T/
//     [ORIGINAL_XDG_CURRENT_DESKTOP] => undefined
//     [SHLVL] => 1
//     [PWD] => /Users/huangbaoyin/Documents/Code/swoole/learning_of_swoole
//     [OLDPWD] => /Users/huangbaoyin/Documents/Code/swoole/learning_of_swoole
//     [HOMEBREW_BOTTLE_DOMAIN] => https://mirrors.ustc.edu.cn/homebrew-bottles
//     [HOMEBREW_PREFIX] => /opt/homebrew
//     [HOMEBREW_CELLAR] => /opt/homebrew/Cellar
//     [HOMEBREW_REPOSITORY] => /opt/homebrew
//     [MANPATH] => /opt/homebrew/share/man:/usr/share/man:/usr/local/share/man:/opt/homebrew/share/man:
//     [INFOPATH] => /opt/homebrew/share/info:/opt/homebrew/share/info:
//     [ZSH] => /Users/huangbaoyin/.oh-my-zsh
//     [PAGER] => less
//     [LESS] => -R
//     [LSCOLORS] => Gxfxcxdxbxegedabagacad
//     [TERM_PROGRAM] => vscode
//     [TERM_PROGRAM_VERSION] => 1.73.0
//     [LANG] => zh_CN.UTF-8
//     [COLORTERM] => truecolor
//     [GIT_ASKPASS] => /Applications/Visual Studio Code.app/Contents/Resources/app/extensions/git/dist/askpass.sh
//     [VSCODE_GIT_ASKPASS_NODE] => /Applications/Visual Studio Code.app/Contents/Frameworks/Code Helper.app/Contents/MacOS/Code Helper
//     [VSCODE_GIT_ASKPASS_EXTRA_ARGS] => --ms-enable-electron-run-as-node
//     [VSCODE_GIT_ASKPASS_MAIN] => /Applications/Visual Studio Code.app/Contents/Resources/app/extensions/git/dist/askpass-main.js
//     [VSCODE_GIT_IPC_HANDLE] => /var/folders/gq/ctrfb6md4yz15qhl5v726k980000gn/T/vscode-git-76ba69c4ce.sock
//     [VSCODE_INJECTION] => 1
//     [ZDOTDIR] => /Users/huangbaoyin
//     [USER_ZDOTDIR] => /Users/huangbaoyin
//     [TERM] => xterm-256color
//     [_] => /opt/homebrew/bin/php
//     [PHP_SELF] => 6-协程高级/4_进程API/env.php
//     [SCRIPT_NAME] => 6-协程高级/4_进程API/env.php
//     [SCRIPT_FILENAME] => 6-协程高级/4_进程API/env.php
//     [PATH_TRANSLATED] => 6-协程高级/4_进程API/env.php
//     [DOCUMENT_ROOT] => 
//     [REQUEST_TIME_FLOAT] => 1668397382.6291
//     [REQUEST_TIME] => 1668397382
//     [argv] => Array
//         (
//             [0] => 6-协程高级/4_进程API/env.php
//         )

//     [argc] => 1
// )