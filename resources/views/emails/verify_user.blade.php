<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>{{config('app.name')}}</title>
<style type="text/css">
.header{font-family:Arial;font-size:1.5em;font-weight:bold;color:#121212;}
.txt{font-family:Arial;font-size:0.9em;color:#121212;}
.txt a{font-family:Arial;font-size:0.9em;color:#0000FF;text-decoration:none;}
.txt a:hover{font-family:Arial;font-size:0.9em;color:#0000FF;text-decoration:none;}
.fttxt{font-family:Arial;font-size:0.8em;color:#121212;line-height:18px}
.txt1{font-family:Arial;font-size:0.8em;color:#121212;}
.txt-red{font-family:Arial;font-size:1.2em;color:#ff0000;}
</style>
</head>

<body>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #ccc;" class="txt">

<!-- <tr><td height="100" colspan="2" align="center" bgcolor="#fff" style="border-top:4px solid #039be5;"><a href="{{env('APP_URL')}}" target="_blank"><img width="150" src="{{ 'public/images/512.png'}}" /></a></td></tr> -->

<tr><td height="10" style="border-top:1px solid #d4d4d4;"></td></tr>

<tr>
<td align="center" class="txt"><strong>Hello {{ $details['name'] }}</strong></td>
</tr>

<tr>
<td style=" padding:10px;"><p>{{$details['body']}} .</p>
<!--  -->


<p></p>

<p><span class="txt-red">Thank you</span><br />
<strong>{{config('app.name')}}</strong></p>
</td>
</tr>

<tr><td>&nbsp;</td></tr>



</table>

</body></html>