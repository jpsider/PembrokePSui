function Invoke-DeployWeb
{
    <#
	.DESCRIPTION
		Deploy .php pages to web server.
    .PARAMETER Path
        A valid Path is required.
	.EXAMPLE
        Invoke-DeployWeb -Destination c:\wamp\www\PembrokePS -Source c:\OpenProjects\ProjectPembroke\PembrokePSUI
	.NOTES
        It will create the directory if it does not exist.
    #>
    [CmdletBinding()]
    [OutputType([boolean])]
    param(
        [System.IO.Path]$Destination,
        [System.IO.Path]$Source
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
        BREAK
    }

}