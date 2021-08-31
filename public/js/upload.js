const $bar = document.getElementById('js--progressbar');
const $alertContainer = document.getElementById('js--alert-container');

let hideBar = function () {
    $bar.style.opacity = "0";

    setTimeout(function () {
        $bar.setAttribute('hidden', 'hidden');
        $bar.style.opacity = "1";
    }, 1000);
}

UIkit.upload('.js-upload', {

    url: '/document/upload',
    multiple: false,
    // allow: "*.pdf",
    name: 'file',
    type: 'json',

    beforeAll: function () {
        $alertContainer.innerHTML = '';
    },
    error: function () {
        console.log('error', arguments);

        const errors = arguments[0].xhr.response;
        for (const error of errors) {
            let alert = crel('div', { 'class': 'uk-alert-danger', 'uk-alert': null },
                crel('a', { 'class': 'uk-alert-close', 'uk-close': null }),
                crel('p', error)
            );

            console.log(alert);

            $alertContainer.appendChild(alert);
        }

        hideBar();
    },
    loadStart: function (e) {
        $bar.removeAttribute('hidden');
        $bar.max = e.total;
        $bar.value = e.loaded;
    },
    progress: function (e) {
        $bar.max = e.total;
        $bar.value = e.loaded;
    },
    loadEnd: function (e) {
        $bar.max = e.total;
        $bar.value = e.loaded;
    },
    completeAll: function () {
        console.log('completeAll', arguments);

        hideBar();
    }
});