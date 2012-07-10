function closePopups(){
    $(".popup").hide();
}
$('.knob').live('click',function(e){
    e.preventDefault();
    closePopups();
})