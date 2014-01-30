/**
 * @author Tsvetan Stoychev <ceckoslab@gmail.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

document.observe("dom:loaded", function() {
    $$('a.edit-order-item-options-link').invoke('observe', 'click', function(event) {
        Event.stop(event);
        var url = this.href;

        new Ajax.Request(url, {
            method:'get',
            onSuccess: function(transport) {
                var response = transport.responseText || "no response text";
                openEditPopup(response);
                bindUpdateOptionsButton();
            },
            onFailure: function() { alert('Something went wrong...'); }
        });
    });
});

function openEditPopup(content) {

    var editPopup = Dialog.info(content, {
        closable : true,
        resizable : false,
        draggable : false,
        className : 'magento',
        windowClassName : 'popup-window',
        title : 'Edit custom options',
        top : 50,
        width : 600,
        height : 450,
        zIndex : 1000,
        recenterAuto : false,
        hideEffect : Element.hide,
        showEffect : Element.show,
        id : 'browser_window'
    });

}

function bindUpdateOptionsButton() {
    $$('#order_custom_options_edit_form .update-options').invoke('observe', 'click', function(event) {
        Event.stop(event);

        //this.disabled = true;

        var form = $('order_custom_options_edit_form');
        var formValidator = new Validation(form);

        var submitUrl = $(form).readAttribute('action');

        if(formValidator.validate()) {
            new Ajax.Request(submitUrl, {
                method : 'post',
                parameters : Form.serialize(form),
                onSuccess : function(transport) {
                    var response = transport.responseText || "no response text";
                    window.location = document.URL;
                },
                onFailure: function() { alert('Something went wrong...'); }
            });
        }
    });
}