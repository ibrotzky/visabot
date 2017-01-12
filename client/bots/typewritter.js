var typewriterElement;

var callback;

// set up text to print, each item in array is new line
var aText = new Array();
var iSpeed = 20; // time delay of print out
var iIndex = 0; // start printing array at this posision
var iArrLength; // the length of the text array
var iScrollAt = 20; // start scrolling up at this many lines

var iTextPos = 0; // initialise text position
var sContents = ''; // initialise contents variable
var iRow; // initialise current row

function typewriter(id, linesArray, callbackFunction) {
    if (id !== undefined) {
        typewriterElement = id;
        aText = linesArray;
        callback = callbackFunction;

        iIndex = 0;
        iTextPos = 0;
        sContents = '';
        iRow = 0;

        if (typeof (aText) === "string")
            aText = [aText];

        iArrLength = aText[0].length;
    }

    sContents = ' ';
    iRow = Math.max(0, iIndex - iScrollAt);
    var destination = document.getElementById(typewriterElement);

    while (iRow < iIndex) {
        sContents += aText[iRow++] + '<br />';
    }

    destination.innerHTML = sContents + aText[iIndex].substring(0, iTextPos);
    
    if (iTextPos++ == iArrLength) {
        iTextPos = 0;
        iIndex++;
        if (iIndex != aText.length) {
            iArrLength = aText[iIndex].length;
            setTimeout(function () { typewriter() }, 500);
        }
        else
            callback();
    } else {
        setTimeout(function () { typewriter() }, iSpeed);
    }
}