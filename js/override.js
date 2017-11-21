/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function redirectCinemaPage(cinemaid) {
    document.cookie = "cinemaid =" + cinemaid;
    window.location = "cinema.php";
}

function redirectMoviePage(movId) {
    document.cookie = "movieID =" + movId;
    window.location = "movie.php";
}

function redirectPaymentPage(showInfoid) {
    document.cookie = "showinfoID =" + showInfoid;
    window.location = "bookTicket.php";
}

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
