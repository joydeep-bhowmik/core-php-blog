//selector
function qs(element){
    return document.querySelector(element);
}
function insertText(txt) {
    //qs("textarea").value+=txt
    document.execCommand("insertText", false, txt);
  }
  function cursorPosition(element) {
    var ctl = element;
    var startPos = ctl.selectionStart;
    var endPos = ctl.selectionEnd;
    return [startPos, endPos];
  }
  function SelectionTextBefore(number) {
    var textbefore = "";
    var activeEl = document.activeElement;
    var activeElTagName = activeEl ? activeEl.tagName.toLowerCase() : null;
    if (
      activeElTagName == "textarea" ||
      (activeElTagName == "input" &&
        /^(?:text|search|password|tel|url)$/i.test(activeEl.type) &&
        typeof activeEl.selectionStart == "number")
    ) {
      textbefore = activeEl.value.slice(
        activeEl.selectionStart - number,
        activeEl.selectionStart
      );
    }
    return textbefore;
  }
  function setSelectionRange(input, selectionStart, selectionEnd) {
    if (input.setSelectionRange) {
      input.focus();
      input.setSelectionRange(selectionStart, selectionEnd);
    } else if (input.createTextRange) {
      var range = input.createTextRange();
      range.collapse(true);
      range.moveEnd("character", selectionEnd);
      range.moveStart("character", selectionStart);
      range.select();
    }
  }
  
function getSelectionText() {
    var text = "";
    var activeEl = document.activeElement;
    var activeElTagName = activeEl ? activeEl.tagName.toLowerCase() : null;
    if (
      activeElTagName == "textarea" ||
      (activeElTagName == "input" &&
        /^(?:text|search|password|tel|url)$/i.test(activeEl.type) &&
        typeof activeEl.selectionStart == "number")
    ) {
      text = activeEl.value.slice(activeEl.selectionStart, activeEl.selectionEnd);
    } else if (window.getSelection) {
      text = window.getSelection().toString();
    }
    return text;
  }
var linebreak="";


function getTextBeforeCursor(textarea){
    var textLines = textarea.value.substr(0, textarea.selectionStart).split("\n");
    var currentLineNumber = textLines.length;
    var currentLine = textLines[textLines.length-1];
    var beforeLine = textLines[textLines.length-2];
   //console.log("\n currentLine is:"+currentLine,"\nbeforeLine is :" + beforeLine)
    if(currentLine=="" || isspace(currentLine)){
        linebreak="";

        if((beforeLine==""|| isspace(beforeLine) || typeof beforeLine == "undefined")){
            linebreak="";

        }else{
            linebreak="\n";
        }
    }else{
        if(typeof beforeLine == "undefined"){
            linebreak="\n\n";
        }else if(currentLine=="" || isspace(currentLine) && (beforeLine==""|| isspace(beforeLine))){
            linebreak="";
    
        }else{
            linebreak="\n\n";
        }

    }

    //console.log(currentLine,beforeLine)
    return [currentLine,beforeLine]
 }
 s(".editor-textarea").on("input",function(){
    getTextBeforeCursor(this)
 })
 s(".editor-textarea").on("pointerup",function(){
    getTextBeforeCursor(this);
 })
 s(".editor-textarea").on("onmouseup",function(){
    getTextBeforeCursor(this);
 })
 s(".editor-textarea").on("select",function(){
    getTextBeforeCursor(this);
 })
function addlink() {
    qs('.editor-textarea').focus();
    if(getSelectionText()){
        insertText( "[link]("+getSelectionText()+")");
       }else{
        insertText( "[link](url)")
        //qs(".editor-textarea").focus();
        position = cursorPosition(qs('.editor-textarea'));
        setSelectionRange(qs('.editor-textarea'), position[0] - 4, position[0] - 1);
       }
  
    }
    function addimage() {
        qs('.editor-textarea').focus();
        if(getSelectionText()){
            insertText( "![image]("+getSelectionText()+")");
           }else{
            insertText( "![image](url)");
            //qs(".editor-textarea").focus();
            position = cursorPosition(qs('.editor-textarea'));
            setSelectionRange(qs('.editor-textarea'), position[0] - 4, position[0] - 1);
           }
       
 
    }
    function bold() {
        qs('.editor-textarea').focus();
    if(getSelectionText()){
        insertText( "**"+getSelectionText()+"**");
       }else{
        insertText( "****")
        //qs(".editor-textarea").focus();
        position = cursorPosition(qs('.editor-textarea'));
        setSelectionRange(qs('.editor-textarea'), position[0], position[0] - 2);
       }
    }
    
    function italic() {
        qs('.editor-textarea').focus();
    if(getSelectionText()){
        insertText( "*"+getSelectionText()+"*");
       }else{
        insertText( "*italic*")
        //qs(".editor-textarea").focus();
        position = cursorPosition(qs('.editor-textarea'));
        setSelectionRange(qs('.editor-textarea'), position[0] - 7, position[0] - 1);
       }
    }
    
    function list() {
        qs('.editor-textarea').focus();
    if(getSelectionText()){
        insertText( linebreak+addPrefix(getSelectionText(),"- ")+"\n");
       }else{
        insertText( "- list ")
        //qs(".editor-textarea").focus();
        position = cursorPosition(qs('.editor-textarea'));
        setSelectionRange(qs('.editor-textarea'), position[0] - 6, position[0] - 1);
       }
    }
    
    function blockquote() {
        qs('.editor-textarea').focus();
    if(getSelectionText()){
        
        insertText( linebreak+"> "+getSelectionText()+"\n");
       }else{
        insertText( linebreak+"> blockquote ")
        //qs(".editor-textarea").focus();
        position = cursorPosition(qs('.editor-textarea'));
        setSelectionRange(qs('.editor-textarea'), position[0] - 11, position[0] - 1);
       }
    }
    
    function h2() {
        qs('.editor-textarea').focus();
    if(getSelectionText()){
        insertText( linebreak+"## "+getSelectionText()+" ");
        console.log(getSelectionText())
       }else{
        insertText( linebreak+"## heading2 ")
        //qs(".editor-textarea").focus();
        position = cursorPosition(qs('.editor-textarea'));
        setSelectionRange(qs('.editor-textarea'), position[0] - 9, position[0] - 1);
       }
    }
    function h3() {
        qs('.editor-textarea').focus();
        if(getSelectionText()){
            insertText( linebreak+"### "+getSelectionText()+" ");
           }else{
            insertText( linebreak+"### heading ")
            //qs(".editor-textarea").focus();
            position = cursorPosition(qs('.editor-textarea'));
            setSelectionRange(qs('.editor-textarea'), position[0] - 9, position[0] - 1);
           }
        }
        
    function code() {
        qs('.editor-textarea').focus();
        if(getSelectionText()){
            insertText( linebreak+"```\n"+getSelectionText()+"\n```"+linebreak);
            console.log(getSelectionText())
           }else{
            insertText( linebreak+"``` \n \n```"+linebreak);
            //qs(".editor-textarea").focus();
            position = cursorPosition(qs('.editor-textarea'));
            setSelectionRange(qs('.editor-textarea'), position[0], position[0]-5);
           }
        }
    function emoji(){
        const button = qs('.emoji-btn');
    }
    ;

  //manage
  var postId=s('.manage').attr('post-id');
  s('.manage .pin').on('click',function(){
    var type='pin';
    var succesMsg='unpin';
    if(this.innerHTML=='unpin') {
      type='unpin';
      succesMsg='pin';
    }
    
    s.ajax({
      url:'api',
      method:'get',
      data:{
        id:postId,
        type:type,
      },
      success:function(res){
        if(res==1){
          s('.pin').html(succesMsg);
        }
      }
    })
  });
  s('.manage .delete').on('click',function(){
    if(!confirm("Are you sure? this action cannot be undo")) return;
    s.ajax({
      url:'api',
      method:'get',
      data:{
        id:postId,
        type:'delete',
      },
      success:function(res){
        if(res==1){
          s('.delete').html('deleted!');
        }
      }
    })
  });