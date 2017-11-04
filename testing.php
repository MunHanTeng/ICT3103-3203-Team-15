<html>
<head>
<title>Back Button Demo: Page One</title>
<script>
function backButtonOverride()
{
  // Work around a Safari bug
  // that sometimes produces a blank page
  setTimeout("backButtonOverrideBody()", 1);

}

function backButtonOverrideBody()
{
  // Works if we backed up to get here
  try {
    history.forward();
  } catch (e) {
    // OK to ignore
  }
  // Every quarter-second, try again. The only
  // guaranteed method for Opera, Firefox,
  // and Safari, which don't always call
  // onLoad but *do* resume any timers when
  // returning to a page
  setTimeout("backButtonOverrideBody()", 500);
}
</script>
</head>
<body onLoad="backButtonOverride()">
<h1>Back Button Demo: Page One</h1>
<a href="MainMovie.php">Advance to Page Two</a>
</body>
</html>