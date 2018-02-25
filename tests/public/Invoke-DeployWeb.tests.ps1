$script:ModuleName = 'PembrokePSui'

Describe "Invoke-DeployWeb function for $moduleName" {
    It "Should Return true if the copy succeeds." {
        Mock -CommandName 'Copy-Item' -MockWith {
            return $true
        }
        Invoke-DeployWeb -Destination "C:\wamp\www\PembrokePS" -Source "C:\OPEN_PROJECTS\ProjectPembroke\PembrokePSui\PembrokePSui\php\" | Should be $true
    }
    It "Should not Throw if the copy succeeds." {
        Mock -CommandName 'Copy-Item' -MockWith {}
        {Invoke-DeployWeb -Destination "C:\wamp\www\PembrokePS" -Source "C:\OPEN_PROJECTS\ProjectPembroke\PembrokePSui\PembrokePSui\php\"} | Should -Not -Throw
    }
    It "Should Throw an exception if the copy fails." {
        Mock -CommandName 'Copy-Item' -MockWith {
            Throw 'This should have thrown an error.'
        }
        Mock -CommandName 'Write-Error' -MockWith {}
        {Invoke-DeployWeb -Destination "C:\wamp\www\PembrokePS" -Source "C:\OPEN_PROJECTS\ProjectPembroke\PembrokePSui\PembrokePSui\php\"} | Should -Throw
        Assert-MockCalled -CommandName 'Write-Error' -Times 1 -Exactly
    }
}