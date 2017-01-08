(function() {
$(document).ready(function() {

    var _paginationBlock = $('.oc-shopaholic-pagination');

    // pagination
    _paginationBlock.on('click', 'a[data-page]',  function(e) {
        e.preventDefault();

        var _this = $(this),
            _thisDataPage = _this.attr('data-page');

        searchStrGen('page', _thisDataPage);
        
        //send ajax request
        if(typeof paginationShopaholicAjaxRequest == 'function') {
            paginationShopaholicAjaxRequest(_this);
        }
    });

    // sorting
    $('body').on('change', '.oc-shopaholic-sorting', function() {
        var _this = $(this),
            _val = _this.val();
        
        searchStrGen('sort', _val);

        //send ajax request
        if(typeof sortingShopaholicAjaxRequest == 'function') {
            sortingShopaholicAjaxRequest(_this);
        }
    });

    /**
     * Generate search string
     * @param name
     * @param val
     */
    function searchStrGen(name, val) {
        var _searchResult = '',
            _arSearch = location.search.split('&');

        if(_arSearch[0][0] == '?') {
            _arSearch[0] = _arSearch[0].slice(1);
        }

        for(var i = 0; i < _arSearch.length; i++) {
            if(_arSearch[i].indexOf(name) == -1 && _arSearch[i] !== '') {
                _searchResult += _arSearch[i] + '&';
            }
        }

        _searchResult += name + '=' + val + '&';

        history.replaceState(null, null, location.origin + location.pathname + '?' + _searchResult.slice(0, -1));
    }
});
})(jQuery);