var dom = require("ace/lib/dom");
var commands = require("ace/commands/default_commands").commands;

// add command for all new editors
commands.push({
    name: "Toggle Fullscreen",
    bindKey: "F11",
    exec: function(editor){
        toggleFullscreenMode(editor);
    }
});

var editor = ace.edit("editor");
editor.setTheme("ace/theme/twilight");
editor.getSession().setMode("ace/mode/c_cpp");


var initEditor = function(){
	var initValue = "/* Project FOPT */\n"
                + "\n"
                + "\tunsigned int Info(unsigned int len, double bit_error_rate)\n"
                + "\t{\n"
                + "\t\treturn len * 2;\n"
                + "\t}\n"
                + "\n"
                + "\n"
                + "\tvoid Encoding(unsigned char SourceData[], unsigned char DestinationData[], int len, double BER)\n"
                + "\t{\n"
                + "\t\tmemcpy(DestinationData, SourceData, len);\n" 
   				+ "\t\tmemcpy(DestinationData + len, SourceData, len);\n"
                + "\t}\n"
                + "\n"
                + "\n"
    			+ "\tvoid Decoding(unsigned char EncodedData[], unsigned char DestinationData[], int len, double BER)\n"
    			+ "\t{\n"
    			+ "\t\tmemcpy(EncodedData, DestinationData,  len / 2);\n"
    			+ "\t}";
    editor.setValue(initValue);
    editor.clearSelection();
};

var toggleFullscreenMode = function(editor) {
    dom.toggleCssClass(document.body, "fullScreen");
    dom.toggleCssClass(editor.container, "fullScreen-editor");
    editor.resize();

    if( $('#restorescreen').css('display')=="none" ){
        $('#restorescreen').show(500, 'swing');
    }else{
        $('#restorescreen').hide();
    }
};

var saveTestAsFile = function(){
    var textToWrite = editor.getValue();
    var textFileAsBlob = new Blob([textToWrite], {type:'text/plain'});
    var fileNameToSaveAs = "FOPT_Proejct.cpp";

    var downloadLink = document.createElement("a");
    downloadLink.download = fileNameToSaveAs;
    downloadLink.innerHTML = "Download File";

    var Browser = {
        chk : navigator.userAgent.toLowerCase()
    };

    console.log(Browser.chk);  

    Browser = {
        ie : Browser.chk.indexOf('msie') != -1,
        ie6 : Browser.chk.indexOf('msie 6') != -1,
        ie7 : Browser.chk.indexOf('msie 7') != -1,
        ie8 : Browser.chk.indexOf('msie 8') != -1,
        ie9 : Browser.chk.indexOf('msie 9') != -1,
        ie10 : Browser.chk.indexOf('msie 10') != -1,
        ie11 : Browser.chk.indexOf('trident') != -1,
        opera : !!window.opera,
        safari : Browser.chk.indexOf('safari') != -1,
        safari3 : Browser.chk.indexOf('applewebkir/5') != -1,
        mac : Browser.chk.indexOf('mac') != -1,
        chrome : Browser.chk.indexOf('chrome') != -1,
        firefox : Browser.chk.indexOf('firefox') != -1
    };
    
    if ((Browser.ie9) || (Browser.ie10) || (Browser.ie11)) {
        
            var oWin = window.open("about:blank", "_blank");
            oWin.document.write(editor.getValue());
            oWin.document.close();
            var success = oWin.document.execCommand('SaveAs', true, "FOPT_Proejct.cpp");
            oWin.close();
            if (!success)
                alert("Sorry, your browser does not support this feature");

    } else {
        
        if (window.webkitURL != null)
        {
            // Chrome allows the link to be clicked
            // without actually adding it to the DOM.
            downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
        }
        else
        {
            // Firefox requires the link to be added to the DOM
            // before it can be clicked.
            downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
            downloadLink.onclick = destroyClickedElement;
            downloadLink.style.display = "block";
            downloadLink.style.innerHTML = "block";
            //downloadLink.click();
            
            document.body.appendChild(downloadLink);
            
        }
        downloadLink.click();
    }
    
};

var destroyClickedElement = function(event){
    document.body.removeChild(event.target);
};



var formSubmit = function(){
    $('<input>').attr({
        type: 'hidden',
        name: 'content',
        value: editor.getValue()
    }).appendTo('form');

    $('#editor_form').submit();
};


$(document).ready(function(){
    //initEditor();
});

$('#fullscreen').click(function(){
	toggleFullscreenMode(editor);
});

$('#restorescreen').click(function(){
    toggleFullscreenMode(editor);
});

$('#loadSampleFile').click(function(){
	loadSampleFile();
});

$('#savefile').click(function(){
    saveTestAsFile();
});

$('#reset').click(function(){
    initEditor();
});

$('#search').click(function(){
    editor.find($('#searchKeyword').val());
});

$('#send').click(function(){
    formSubmit();
});

