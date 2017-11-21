/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function validate() {
    var isValid = true;
    var form1 = document.getElementById('STARTLOGIN');
    document.getElementById('STARTLOGIN').style.display = 'none';

    if (isValid) {
        document.getElementById('form1').style.display = 'block';
        return false;
    }
}
