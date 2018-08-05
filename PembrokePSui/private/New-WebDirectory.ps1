function New-WebDirectory
{
    <#
	.DESCRIPTION
		Create Destination Directory.
    .PARAMETER Destination
        A valid Path String is required.
	.EXAMPLE
        New-WebDirectory -Destination c:\wamp\www\PembrokePS
	.NOTES
        It will create the directory if it does not exist.
    #>
    [CmdletBinding(
        SupportsShouldProcess = $true,
        ConfirmImpact = "Low"
    )]
    [OutputType([boolean])]
    param(
        [Parameter(Mandatory = $true)][String]$Destination
    )
    begin
    {
        # Nothing to see here.
    }
    process
    {
        if ($pscmdlet.ShouldProcess("Creating New Directory."))
        {
            try
            {
                if(Test-Path -Path "$Destination") {
                    return $true
                } else {
                    New-Item -Path "$Destination" -ItemType Directory -Force -Confirm:$false
                }
            }
            catch
            {
                $ErrorMessage = $_.Exception.Message
                $FailedItem = $_.Exception.ItemName
                Throw "Invoke-CreateWebDirectory: $ErrorMessage $FailedItem"
            }
        }
        else
        {
            # -WhatIf was used.
            return $false
        }
    }
}