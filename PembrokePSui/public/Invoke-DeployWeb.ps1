function Invoke-DeployWeb
{
    <#
	.DESCRIPTION
		Deploy .php pages to web server.
    .PARAMETER Destination
        A valid Path String is required.
    .PARAMETER Source
        A valid Path String is required.
	.EXAMPLE
        Invoke-DeployWeb -Destination c:\wamp\www\PembrokePS -Source c:\OpenProjects\ProjectPembroke\PembrokePSUI
	.NOTES
        It will create the directory if it does not exist.
    #>
    [CmdletBinding()]
    [OutputType([boolean])]
    param(
        [String]$Destination = 'C:\wamp\www\PembrokePS',
        [Parameter(Mandatory = $true)][String]$Source
    )
    try
    {
        Copy-Item -Path $Source -Destination $Destination -Recurse -Confirm:$false -Force
    }
    catch
    {
        $ErrorMessage = $_.Exception.Message
        $FailedItem = $_.Exception.ItemName		
        Write-Error "Error: $ErrorMessage $FailedItem"
        Throw $_
    }

}