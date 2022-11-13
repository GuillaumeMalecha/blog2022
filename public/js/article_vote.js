//fichier js pour gérer les votes présents sur les articles

var $container = $('.js-vote-arrows');
$container.find('a').on('click', function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);

    $.ajax({
        url: '/article/'+$link.data('numero')+'/vote/'+$link.data('direction'),
        method: 'POST'
    }).then(function (response) {
        $container.find('.js-vote-total').text(response.votes);
    });
});
