$(document).ready(function () {
    let search = $('#search-user');
    let searchLeader = $('#search-userLeader');
    const url_string = window.location.href;
    let url = new URL(url_string);
    let id = url.searchParams.get("id")
    search.keyup(function () {
        fetchData();
    })
    searchLeader.keyup(function () {
        fetchDataLeader();
    })

    function fetchData() {
        let searchValue = search.val();
        let dropdown = $('#dropdown');
        if (search === '') {
            dropdown.css('display', 'none');
        }
        $.post('/list-user-api', {
            searchValue: searchValue
        }, function (data) {
            if (data !== 'Not found!') {
                dropdown.css('display', 'block')
                dropdown.html(data)
                $('li').click(function () {
                    search.val($(this).text().replace(/\s/g, ""));
                })
            }
        })
    }

    function fetchDataLeader() {
        let searchValue = searchLeader.val();
        let dropdown = $('#dropdown-2');
        if (searchLeader === '') {
            dropdown.css('display', 'none');
        }
        $.post('/list-valid-user-api', {
            searchValue: searchValue,
            idProject: id
        }, function (data, status) {
            if (data !== 'Not found!') {
                dropdown.css('display', 'block')
                dropdown.html(data)
                $('li').click(function () {
                    searchLeader.val($(this).text().replace(/\s/g, ""));
                })
            }
        })
    }

    let add = $('#add-user');
    let addLeader = $('#add-userLeader');
    add.click(function () {
        let myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            keyboard: false
        })
        let myModalSuccess = new bootstrap.Modal(document.getElementById('exampleModalToggle2'), {
            keyboard: false
        })
        let myModalFail = new bootstrap.Modal(document.getElementById('exampleModalToggle3'), {
            keyboard: false
        })
        let failButton = $('.button-fail');
        let successButton = $('.button-success');
        let searchValue = search.val();
        $.post('/project', {
            searchValue: searchValue,
            idProject: id
        }, function (data, status) {
            if (data == true) {
                myModalSuccess.show()
                successButton.click(function () {
                    myModal.hide()
                    myModalSuccess.hide()
                })
                return true;
            } else {
                myModalFail.show()
                failButton.click(function () {
                    myModalFail.hide()
                })
            }
        })
    })
    addLeader.click(function () {
        let myModalLeader = new bootstrap.Modal(document.getElementById('exampleModalLeader'), {
            keyboard: false
        })
        let myModalSuccessLeader = new bootstrap.Modal(document.getElementById('exampleModalToggle2Leader'), {
            keyboard: false
        })
        let myModalFailLeader = new bootstrap.Modal(document.getElementById('exampleModalToggle3Leader'), {
            keyboard: false
        })
        let failButtonLeader = $('.button-failLeader');
        let successButtonLeader = $('.button-successLeader');
        let searchValue = searchLeader.val();
        $.post('/set-leader', {
            searchValue: searchValue,
            idProject: id
        }, function (data, status) {
            console.log(data)
            if (data == true) {
                myModalSuccessLeader.show()
                successButtonLeader.click(function () {
                    myModalLeader.hide()
                    myModalSuccessLeader.hide()
                })
                return true;
            } else {
                myModalFailLeader.show()
                failButtonLeader.click(function () {
                    myModalFailLeader.hide()
                })
            }
        })
    })
})