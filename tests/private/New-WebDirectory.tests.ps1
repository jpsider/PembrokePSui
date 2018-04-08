$script:ModuleName = 'PembrokePSui'

Describe "New-WebDirectory function for $moduleName" {
    function Test-Path{}
    function New-Item{}
    It "Should not throw if the folder exists" {
        Mock -CommandName 'Test-Path' -MockWith {
            return $true
        }
        Mock -CommandName 'New-Item' -MockWith {}
        {New-WebDirectory -Destination "C:\wamp\www\PembrokePS"} | Should -not -Throw
        Assert-MockCalled -CommandName 'Test-Path' -Times 1 -Exactly
        Assert-MockCalled -CommandName 'New-Item' -Times 0 -Exactly
    }
    It "Should Throw an exception if the New-Item fails." {
        Mock -CommandName 'Test-Path' -MockWith {
            return $false
        }
        Mock -CommandName 'New-Item' -MockWith {
            Throw 'This should have thrown an error.'
        }
        {New-WebDirectory -Destination "C:\wamp\www\PembrokePS"} | Should -Throw
        Assert-MockCalled -CommandName 'Test-Path' -Times 2 -Exactly
        Assert-MockCalled -CommandName 'New-Item' -Times 1 -Exactly
    }
    It "Should Not throw if the Path does not exist and the folder is created." {
        Mock -CommandName 'Test-Path' -MockWith {
            return $false
        }
        {New-WebDirectory -Destination "C:\wamp\www\PembrokePS"} | Should -Throw
        Assert-MockCalled -CommandName 'Test-Path' -Times 3 -Exactly
    }
    It "Should return false when -WhatIf is used." {
        New-WebDirectory -Destination "C:\wamp\www\PembrokePS" -WhatIf | Should be $false
    }
}