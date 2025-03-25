<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body {
            background-color: #f0f2f5;
        }

        .navbar {
            background: linear-gradient(135deg, #4b6cb7, #182848);
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #4b6cb7, #182848);
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff7eb3, #ff758c);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Notes App</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-dark">My Notes</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#noteModal">+ Add Note</button>
        </div>
        <div class="row" id="notesContainer"></div>
    </div>

    <div class="modal fade" id="noteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="noteId">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" class="form-control" placeholder="Enter note title">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea id="content" class="form-control" rows="4" placeholder="Enter note content"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveNote" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function fetchNotes() {
                $.get("https://backend-dhidi-965240686389.us-central1.run.app/notes", function(data) {
                    let cards = "";
                    data.forEach(note => {
                        cards += `<div class='col-md-4'>
                            <div class='card mb-3 p-3'>
                                <div class='card-body'>
                                    <h5 class='card-title text-primary'>${note.title}</h5>
                                    <p class='card-text text-muted'>${note.content}</p>
                                    <div>
                                        <button class='btn btn-warning text-white btn-sm edit' data-id='${note.id}'>
                                            <i data-feather="edit"></i>
                                        </button>
                                        <button class='btn btn-danger btn-sm delete' data-id='${note.id}'>
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                    $("#notesContainer").html(cards);
                    feather.replace();
                });
            }

            fetchNotes();

            $("#saveNote").click(function() {
                let id = $("#noteId").val().trim();
                let title = $("#title").val().trim();
                let content = $("#content").val().trim();

                if (title === "" || content === "") {
                    alert("Title and content cannot be empty!");
                    return;
                }

                let method = id ? "PATCH" : "POST";
                let url = id ? `https://backend-dhidi-965240686389.us-central1.run.app/notes/${id}` : "https://backend-dhidi-965240686389.us-central1.run.app/notes";

                $.ajax({
                    url: url,
                    method: method,
                    contentType: "application/json",
                    data: JSON.stringify({
                        title,
                        content
                    }),
                    success: function() {
                        $("#noteModal").modal("hide");
                        fetchNotes();
                        $("#noteId").val(""); // Reset ID setelah berhasil
                        $("#title").val("");
                        $("#content").val("");
                    }
                });
            });

            $(document).on("click", ".edit", function() {
                let id = $(this).data("id");
                $.get(`https://backend-dhidi-965240686389.us-central1.run.app/notes/${id}`, function(data) {
                    $("#noteId").val(data.id);
                    $("#title").val(data.title);
                    $("#content").val(data.content);
                    $("#noteModal").modal("show");
                });
            });

            $(document).on("click", ".delete", function() {
                let id = $(this).data("id");
                if (confirm("Are you sure you want to delete this note?")) {
                    $.ajax({
                        url: `https://backend-dhidi-965240686389.us-central1.run.app/notes/${id}`,
                        method: "DELETE",
                        success: function() {
                            fetchNotes();
                        }
                    });
                }
            });

            // Reset form setiap kali tombol "Add Note" diklik
            $('[data-bs-target="#noteModal"]').click(function() {
                $("#noteId").val(""); // Kosongkan ID agar tidak mengupdate catatan sebelumnya
                $("#title").val("");
                $("#content").val("");
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
