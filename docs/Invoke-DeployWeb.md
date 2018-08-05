---
external help file: PembrokePSui-help.xml
Module Name: PembrokePSui
online version:
schema: 2.0.0
---

# Invoke-DeployWeb

## SYNOPSIS

## SYNTAX

```
Invoke-DeployWeb [[-Destination] <String>] [-Source] <String> [<CommonParameters>]
```

## DESCRIPTION
Deploy .php pages to web server.

## EXAMPLES

### EXAMPLE 1
```
Invoke-DeployWeb -Destination c:\wamp\www\PembrokePS -Source c:\OpenProjects\ProjectPembroke\PembrokePSui\PembrokePSui\php
```

## PARAMETERS

### -Destination
A valid Path String is required for destination.

```yaml
Type: String
Parameter Sets: (All)
Aliases:

Required: False
Position: 1
Default value: C:\wamp\www\PembrokePS
Accept pipeline input: False
Accept wildcard characters: False
```

### -Source
A valid Path String is required.

```yaml
Type: String
Parameter Sets: (All)
Aliases:

Required: True
Position: 2
Default value: None
Accept pipeline input: False
Accept wildcard characters: False
```

### CommonParameters
This cmdlet supports the common parameters: -Debug, -ErrorAction, -ErrorVariable, -InformationAction, -InformationVariable, -OutVariable, -OutBuffer, -PipelineVariable, -Verbose, -WarningAction, and -WarningVariable.
For more information, see about_CommonParameters (http://go.microsoft.com/fwlink/?LinkID=113216).

## INPUTS

## OUTPUTS

### System.Boolean

## NOTES
It will create the directory if it does not exist.

## RELATED LINKS
