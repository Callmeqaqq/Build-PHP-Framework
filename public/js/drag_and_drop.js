$(document).ready(function () {
    const url_string = window.location.href;
    let url = new URL(url_string);
    let id = url.searchParams.get("id")

    let KanbanTest = new jKanban({
        element: "#myKanban",
        gutter: "12px",
        widthBoard: "300px",
        dragBoards: false,
        itemHandleOptions: {
            enabled: false,
        },
        dropEl: function (el, target) {
            let focusOnBoard = target.parentElement.dataset.order;
            let dragItemId = el.dataset.eid;
            let dragItemStatusFinal = el.parentElement.parentElement.dataset.order;
            console.log(dragItemId, dragItemStatusFinal)
            if (dragItemStatusFinal === focusOnBoard) {
                $.post('/move-task-status', {
                    idTask: dragItemId,
                    statusId: dragItemStatusFinal
                })
            }
        },
        boards: [
            {
                id: "_todo", title: "To Do List", class: "info, good", dragTo: ["_working"], item: []
            },
            {
                id: "_working", title: "Working List", class: "working", dragTo: ["_test"], item: [],
            },
            {
                id: "_test", title: "Wait For Test", class: "test", dragTo: ["_error", "_done"], item: []
            },
            {
                id: "_error", title: "Bugs", class: "error", dragTo: ["_working"], item: []
            },
            {
                id: "_done", title: "Well Done", class: "finished", dragTo: [], item: []
            }
        ]
    });

    //new task in To Do List
    let toDoButton = document.getElementById("addToDo");
    if (toDoButton) {
        toDoButton.addEventListener("click", function () {
            $.post('/create-new-task', {
                idProject: id,
                statusId: 1,
                title: "New Default Task"
            })
            KanbanTest.addElement("_todo", {
                title: "New Default Task",
                click: function (el) {
                    Swal.fire({
                        title: '<strong> New Default Task </strong>',
                        width: 1300,
                        html:
                            '<strong>Status :</strong> ' + "statusOfTask" +
                            '<br><strong>Deadline at :</strong> ' + "element.deadline" +
                            '<br><strong>Descriptions : </strong>' + "element.des" +
                            '<br><strong>Doing by :</strong> ' + "element.doing_by"
                        ,
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                            '<i class="fa fa-thumbs-up"></i> Great!',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        cancelButtonText:
                            '<i class="fa fa-thumbs-down"></i>',
                        cancelButtonAriaLabel: 'Thumbs down'
                    })
                }
            });
        });
    }

    let Statistical = $('#showStatistical')
    if (Statistical) {
        Statistical.click(function () {
            fetch('/get-task-statistical/' + id)
                .then(res => res.json())
                .then(data => {
                    let thongke = '';
                    data.map( e => {
                        thongke += `${e}`
                    })
                    Swal.fire({
                        title: '<strong>Statistical</strong>',
                        icon: 'info',
                        width: 1200,
                        html:
                            'thongke',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText:
                            '<i class="fa fa-thumbs-up"></i> Great!',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        cancelButtonText:
                            '<i class="fa fa-thumbs-down"></i>',
                        cancelButtonAriaLabel: 'Thumbs down'
                    })
                })
                .catch(error => console.log(error))
        })
    }

    //remove element
    // let removeElement = document.getElementById("removeElement");
    // removeElement.addEventListener("click", function () {
    //     KanbanTest.removeElement("_test_delete");//id of item
    // });

    let renderTask = (arrayKeyOfObj, board_id) => {
        let accessCheck = $('#access').html();
        arrayKeyOfObj.forEach(element => {
            let statusOfTask = '<span class="text-success">Still OK.</span>';
            let classOfTask = ['task-row'];
            let deadline = new Date(Date.parse(element.deadline.replace(/-/g, '/')))
            let TheDate = new Date();
            let timeDifference = deadline.getTime() - TheDate.getTime();
            let timeRemaining = timeDifference / (1000 * 3600 * 24)
            if (timeRemaining <= 1) {
                statusOfTask = '<span class="text-danger">Going out of time!</span>';
                classOfTask = ['task-row', 'text-danger']
            }
            let timestampHandle = element.deadline.split(/[ ,]+/);
            let timestampDate = timestampHandle[0]
            let timestampHours = timestampHandle[1]
            KanbanTest.addElement(board_id, {
                id: element.id,
                title: element.title,
                class: classOfTask,
                click: function (el) {
                    let urlGetTask = "/get-task-comments/" + element.id
                    let strHTML = ''
                    let title = ''
                    let btnConfirm = true
                    let btnDeny = true
                    let btnCancel = true
                    if (accessCheck === 'MENTOR') {
                        strHTML = '<p style="text-align: center"><strong>Status :</strong><span > ' + statusOfTask + '</span> || ' +
                            '<span><strong>Deadline at : </strong>' +
                            '<input style="width: 120px" type="text" class="EndDateOfTask" placeholder="yyyy/mm/dd" value="' + timestampDate + '">' + '</span>' +
                            '<input style="width: 120px" type="text" class="EndTimeOfTask" placeholder="hh:mm:ss" value="' + timestampHours + '">' + '</span> </p>' +
                            '<strong>Descriptions : </strong><br><textarea class="form-control DesOfTask" name="" cols="120" rows="3">' + element.des + '</textarea>' +
                            '<br><strong>Doing by :</strong> ' + (Object.values(element.doing_by)) +
                            '<br><br><strong>Rating : <input  style="width: 80px" type="text" class="RatingOfTask" placeholder="1-100"></strong>' +
                            '<br>'
                        title = '<input class="TitleOfTask" type="text" value="' + element.title + '">'
                    } else {
                        strHTML =
                            '<p style="text-align: center"><strong>Status :</strong><span> ' + statusOfTask + '</span> || <span><strong>Deadline at : </strong>' + element.deadline + '</span> </p>' +
                            '<strong>Descriptions : </strong>' + element.des +
                            '<br><br><strong>Doing by :</strong> ' + (Object.values(element.doing_by)) +
                            '<br><br><strong>Rating :</strong>' + element.rating + '<br>'
                        title = '<strong>' + element.title + '</strong>'
                        btnConfirm = false
                        btnDeny = false
                    }
                    Swal.fire({
                        title: title,
                        width: 1300,
                        html: strHTML,
                        focusConfirm: false,
                        showCancelButton: btnCancel,
                        showConfirmButton: btnConfirm,
                        showDenyButton: btnDeny,
                        confirmButtonText: 'Save',
                        denyButtonText: `Add user to task`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let task_name = $('.TitleOfTask').val()
                            let task_des = $('.DesOfTask').val()
                            let rating = $('.RatingOfTask').val()
                            let taskDate = $('.EndDateOfTask').val().concat(" ", $('.EndTimeOfTask').val())
                            console.log(task_name, task_des, rating, taskDate)
                            $.post('/update-task-info', {
                                task_name: task_name,
                                task_des: task_des,
                                rating: rating,
                                date: taskDate,
                                id: element.id
                            }, function (data) {
                                console.log(data)
                            })
                            Swal.fire('Saved!', '', 'success')
                        } else if (result.isDenied) {
                            Swal.fire({
                                title: '<strong>Add User</strong>',
                                icon: 'info',
                                html:
                                    '<div class="input-group mb-3">\n' +
                                    '  <span class="input-group-text" id="basic-addon1">@</span>\n' +
                                    '  <input id="input-add-user" type="text" class="form-control" placeholder="Enter member\'s Email" >\n' +
                                    '</div>',
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                            })
                                .then((result) => {
                                    if (result.isConfirmed) {
                                        let findUser = $('#input-add-user').val();
                                        $.post('/check-user-unique-in-task', {
                                            email: findUser,
                                            projectId: element.id
                                        }, function (data) {
                                            if (data == true) {
                                                Swal.fire('Saved!', '', 'success')
                                            } else {
                                                Swal.fire('get error there!', '', 'error')
                                            }
                                        })
                                    }
                                })
                        }
                    })
                    fetch(urlGetTask)
                        .then(res => res.json())
                        .then(data => {
                            let htmlOfSweet = $('#swal2-html-container')
                            htmlOfSweet.append("<br><div class=\"input-group mb-3\">\n" +
                                "  <span class=\"input-group-text\" id=\"basic-addon1\">Comment</span>\n" +
                                "  <input type=\"text\" class=\"form-control\" placeholder=\"Comment\" aria-label=\"Username\" aria-describedby=\"basic-addon1\">\n" +
                                "</div>" +
                                "<button  class='btn-add-comment btn-sm btn btn-outline-dark'>Add comment</button>" +
                                "<div id='div-comment' class=\"my-3 p-3 bg-body rounded shadow-sm\">\n" +
                                "    <h6 class=\"border-bottom pb-2 mb-0\">Recent comment</h6>\n" +
                                "  </div>")
                            $('.btn-add-comment').click(function () {
                                let comment = $(this).prev().children("input").val();
                                $('#div-comment').append("<div class=\"d-flex text-muted pt-3\">" +
                                    "<p class=\"pb-3 mb-0 small lh-sm border-bottom\">" +
                                    "<strong class=\"d-block text-gray-dark\">@you</strong>" + comment +
                                    "</p>" +
                                    "</div>")
                                $.post('/add-comment', {
                                    comment: comment,
                                    id: element.id
                                })
                            })
                            let str = '';
                            data.map(e => {
                                console.log(e)
                                str += `<div class="d-flex text-muted pt-3">
                                      <p class="pb-3 mb-0 small lh-sm border-bottom">
                                        <strong class="d-block text-gray-dark">@${e.user_id}</strong>
                                        ${e.body}
                                        </p>
                                    </div>
                                `
                            })
                            $('#div-comment').append(str)
                        })
                        .catch(error => console.log(error))
                }
            })
        })
    }

    //fetch task
    fetch('/get-all-task/' + id)
        .then(res => res.json())
        .then(data => {
            console.log(data)
            if (data.todo) {
                renderTask(data.todo, '_todo');
            }
            if (data.working) {
                renderTask(data.working, '_working');
            }
            if (data.test) {
                renderTask(data.test, '_test');
            }
            if (data.error) {
                renderTask(data.error, '_error');
            }
            if (data.done) {
                renderTask(data.done, '_done');
            }
        })
        .catch(error => console.log(error))
})

