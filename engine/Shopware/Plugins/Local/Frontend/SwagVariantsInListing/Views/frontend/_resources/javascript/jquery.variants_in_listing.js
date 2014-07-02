;(function ($, document) {
 
    $(function() {
        $('.swag_variants_in_listing').startPlugin();
    });
 
    /**
     * Helper function to initial a new instance of
     * the VariantsInListing class for each swag_variants_in_listing
     * container.
     */
    $.fn.startPlugin = function() {
        this.each(function (index, item) {
            new VariantsInListing(item);
        });
    };
 
    /**
     * Definition of a new class
     * @param element
     * @constructor
     */
    function VariantsInListing(element) {
        var me = this;
        me.element = element;
        me.$element = $(me.element);
        me.init();
        me.bindGroupChangeEvent();
    }
 
    /**
     * Handles the initialisation of the plugin.
     * This function collects all required containers and values of the hidden
     * input fields.
     */
    VariantsInListing.prototype.init = function () {
        var me = this;
        me.groups = me.$element.find('.group_selection');
        me.articleId = me.$element.find('input[name=articleId]').val();
        me.requestUrl = me.$element.find('input[name=requestUrl]').val();
        me.coverContainer = me.$element.parents('.artbox').find('.artbox_thumb');
        me.src = me.$element.find('input[name=src]').val()
    };
 
    /**
     * The bindGroupChangeEvent iterates the initialed group selection boxes
     * and binds the change event on them.
     */
    VariantsInListing.prototype.bindGroupChangeEvent = function () {
        var me = this;
 
        $.each(me.groups, function(index, item) {
            var $item = $(item);
 
            $item.bind('change', function(event) {
                me.getNewCover();
            });
        });
    };
 
    /**
     * The getNewCover function collects the group values of the selection boxes
     * and sends an ajax request to our plugin controller to get the new cover
     * for the customer selection.
     */
    VariantsInListing.prototype.getNewCover = function() {
        var me = this, requestData = {
            articleId: me.articleId,
            groups: {}
        };
 
        $.each(me.groups, function(index, item) {
            var $item = $(item);
            requestData.groups[$item.attr('name')] = $item.val();
        });
 
        $.ajax({
            url: me.requestUrl,
            dataType:'json',
            data: requestData,
            success:function (response) {
                if (response.cover) {
                    me.refreshCover(response.cover);
                }
            }
        });
    };
 
    /**
     * Helper function to refresh the cover of a single article box.
     * @param cover
     */
    VariantsInListing.prototype.refreshCover = function(cover) {
        var me = this;
        me.coverContainer.css('background-image', 'url('+cover.src[me.src]+')');
    };
})(jQuery, document);