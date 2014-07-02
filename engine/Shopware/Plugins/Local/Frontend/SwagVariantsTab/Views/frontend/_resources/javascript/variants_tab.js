;(function ($, document) {
    // Add a startPlugin function to the jQuery prototype
    $.fn.startPlugin = function() {
        new VariantTab(this);
    };
 
   // Run when document is ready
    $(function() {
        $(".variants_tab").startPlugin();
    });
 
    function VariantTab(element) {
        var me = this;
        me.element = element;
 
        me.init();
        me.loadVariants();
        me.addPagingContainer();
    }
 
    // Read initial configuration
    VariantTab.prototype.init = function() {
        var me = this;
        me.config = {
            page: 1
        };
 
        me.element.find('.config input').each(function(index, item) {
            me.config[item.name] = item.value;
        });
    };
 
    // Perform an ajax request and add returned variants to the main element
    VariantTab.prototype.loadVariants = function() {
        var me = this;
 
        $.ajax({
            url: me.config.requestUrl,
            dataType:'json',
            data:{
                articleId: me.config.currentArticleId,
                page: me.config.page
            },
            success:function (response) {
 
                me.addVariants(response.data);
                if (response.currentPage >= response.numberOfPages) {
                    me.pagingContainer.remove();
                }
 
                me.config.page++;
            }
        });
    };
 
    // create a variant template for each passed variant
    VariantTab.prototype.addVariants = function(variants) {
        var me = this;
 
        $.each(variants, function(index, variant) {
            me.createVariantTemplate(variant);
        });
    };
 
    /**
     * Adds the paging container to the bottom of the main element
     */
    VariantTab.prototype.addPagingContainer = function() {
        var me = this;
 
        me.pagingContainer = $('<div>', {
            'class':'paging_wrapper'
        });
 
 
        var next = $('<a>', {
            class: 'navi more',
            text: me.config.moreText,
            href: '#'
        });
        next.appendTo(me.pagingContainer);
        next.on('click', function() {
            me.loadVariants();
            return false;
        });
 
        me.pagingContainer.appendTo(me.element);
    };
 
    /**
     * Creates an element for a given variant, appends it to the main element and binds the click event
     * @param variant
     */
    VariantTab.prototype.createVariantTemplate = function(variant) {
        var me = this,
            textClass = 'desc',
            wrapperClass = 'variant_box pointer';
 
        if (variant.number == me.config.currentArticleNumber) {
            textClass = 'selected';
            wrapperClass = 'variant_box';
        }
 
        var wrapper = $('<div>', {
            'class':wrapperClass
        });
        wrapper.variant = variant;
 
        var img = $('<img>', {
            'class': 'variant-image',
            'src':  variant.cover.src[2]
        });
 
        var text = $('<p>', {
            class: textClass,
            text: variant.additionalText
        });
 
        img.appendTo(wrapper);
        text.appendTo(wrapper);
        wrapper.appendTo(me.element);
 
        if (variant.number != me.config.currentArticleNumber) {
            me.bindClickEvent(wrapper);
        }
    };
 
 
    /**
     * Dynamically adds a form element to DOM and submits it in order to perform a proper POST request
     * @param element
     */
    VariantTab.prototype.bindClickEvent = function(element) {
        var me = this;
 
        element.bind('click', function(event) {
            var options = element.variant.configuratorOptions,
                variantId = element.variant.id;
 
            var form = $('<form>', {
                method: 'POST',
                name: 'variant' + variantId
            });
 
            $.each(options, function (i, item) {
                form.append($('<input/>', {
                     type: 'hidden',
                     name: 'group[' + item.groupId + ']',
                     value: item.id
                 }));
            });
            form.appendTo('body').submit();
        });
    };
 
})(jQuery, document);
 