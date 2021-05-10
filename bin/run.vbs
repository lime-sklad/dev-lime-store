DIM objShell
set objShell=wscript.createObject("wscript.shell")
iReturn=objShell.Run("redir.bat", 0, TRUE)