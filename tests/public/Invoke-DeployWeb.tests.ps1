$script:ModuleName = 'PembrokePSui'

Describe "Invoke-DeployWeb function for $moduleName" {
    function Test-Path{}
    function Get-ChildItem{}
    function Copy-Item{}
    function New-WebDirectory{}
    It "Should not throw if the copy succeeds" {
        $RawReturn = @(
             @{
                name = 'Directory1'
            }
            @{
                name = 'Directory2'
            }              
        )
        $ReturnJson = $RawReturn | ConvertTo-Json
        $ReturnData = $ReturnJson | ConvertFrom-json
        Mock -CommandName 'Test-Path' -MockWith {
            return $true
        }
        Mock -CommandName 'Copy-Item' -MockWith {}
        Mock -CommandName 'New-WebDirectory' -MockWith {}
        Mock -CommandName 'Get-ChildItem' -MockWith {
            return $ReturnData
        }
        {Invoke-DeployWeb -Destination "C:\wamp\www\PembrokePS" -Source "C:\OPEN_PROJECTS\ProjectPembroke\PembrokePSui\PembrokePSui\php\"} | Should -not -Throw
        Assert-MockCalled -CommandName 'Test-Path' -Times 1 -Exactly
        Assert-MockCalled -CommandName 'Copy-Item' -Times 3 -Exactly
        Assert-MockCalled -CommandName 'Get-ChildItem' -Times 1 -Exactly
        Assert-MockCalled -CommandName 'New-WebDirectory' -Times 1 -Exactly
    }
    It "Should Throw an exception if the copy fails." {
        $RawReturn = @(
            @{
               name = 'Directory1'
           }
           @{
               name = 'Directory2'
           }              
       )
       $ReturnJson = $RawReturn | ConvertTo-Json
       $ReturnData = $ReturnJson | ConvertFrom-json
        Mock -CommandName 'Test-Path' -MockWith {
            return $true
        }
        Mock -CommandName 'Copy-Item' -MockWith {
            Throw 'This should have thrown an error.'
        }
        Mock -CommandName 'New-WebDirectory' -MockWith {}
        Mock -CommandName 'Get-ChildItem' -MockWith {
            return $ReturnData
        }
        {Invoke-DeployWeb -Destination "C:\wamp\www\PembrokePS" -Source "C:\OPEN_PROJECTS\ProjectPembroke\PembrokePSui\PembrokePSui\php\"} | Should -Throw
        Assert-MockCalled -CommandName 'Test-Path' -Times 2 -Exactly
        Assert-MockCalled -CommandName 'Copy-Item' -Times 4 -Exactly
        Assert-MockCalled -CommandName 'Get-ChildItem' -Times 1 -Exactly
        Assert-MockCalled -CommandName 'New-WebDirectory' -Times 2 -Exactly
    }
    It "Should Throw an exception if the Path does not exist." {
        Mock -CommandName 'New-WebDirectory' -MockWith {}
        Mock -CommandName 'Test-Path' -MockWith {
            return $false
        }
        {Invoke-DeployWeb -Destination "C:\wamp\www\PembrokePS" -Source "C:\OPEN_PROJECTS\ProjectPembroke\PembrokePSui\PembrokePSui\php\"} | Should -Throw
        Assert-MockCalled -CommandName 'Test-Path' -Times 3 -Exactly
        Assert-MockCalled -CommandName 'New-WebDirectory' -Times 3 -Exactly
    }
}