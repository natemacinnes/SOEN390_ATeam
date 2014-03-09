for /f "usebackq" %%m in (`dir /b C:\wamp\bin\php`) do (
    "C:\wamp\bin\php\%%m\cmd.exe"
)
