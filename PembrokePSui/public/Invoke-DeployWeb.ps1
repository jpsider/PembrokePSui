function Invoke-DeployWeb
{
    <#
	.DESCRIPTION
		Deploy .php pages to web server.
    .PARAMETER Destination
        A valid Path String is required for destination.
    .PARAMETER Source
        A valid Path String is required.
	.EXAMPLE
        Invoke-DeployWeb -Destination c:\wamp\www\PembrokePS -Source c:\OpenProjects\ProjectPembroke\PembrokePSui\PembrokePSui\php
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
        New-WebDirectory -Destination $Destination
        if(Test-Path -Path "$Source") {
            Copy-Item -Path "$Source\*" -Destination $Destination -Recurse -Confirm:$false -Force
            $Directories = Get-ChildItem -Path $Source -Directory | Select-Object Name
            $DirectoryCount = ($Directories | Measure-Object).count
            if($DirectoryCount -gt 0){
                foreach($Directory in $Directories){
                    $DirectoryName = $Directory.Name
                    $NewSource = $Source + "\" + $DirectoryName
                    Copy-Item -Path "$NewSource" -Destination $Destination -Container -Recurse -Confirm:$false -Force
                }
            }
        } else {
            Throw "Invoke-DeployWeb: Source Directory: $Source does not exist."
        }
    }
    catch
    {
        $ErrorMessage = $_.Exception.Message
        $FailedItem = $_.Exception.ItemName
        Throw "Invoke-DeployWeb: $ErrorMessage $FailedItem"
    }
}