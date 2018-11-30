$('#add-image').click(function(){
    const index=+$('#widget-counter').val();
    const tmpl=$('#annonce_images').data('prototype').replace(/__name__/g, index);
    $('#annonce_images').append(tmpl);
    $('#widget-counter').val(index+1);
    handleDeleteButtons();
});

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function ({}) {
        const target=this.dataset.target;
        $(target).remove();
    });
}

function updateCounter(){
    const count=+$('#annonce_images div.form-group').length;
    $('#widget-counter').val(count+1);
}

handleDeleteButtons();
updateCounter();