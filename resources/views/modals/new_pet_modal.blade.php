<div class="modal fade" id="addPetModal" tabindex="-1" role="dialog" aria-labelledby="addPetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('pets.store') }}">
            <div class="modal-header">
                <h5 class="modal-title" id="addPetModalLabel">Dodaj nowe zwierzę</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="category">Category Name:</label>
                        <input type="text" class="form-control" id="category" name="category[name]">
                    </div>
                    <div class="form-group">
                        <label for="photoUrls">Photo URL:</label>
                        <input type="text" class="form-control" id="photoUrls" name="photoUrls[0]">
                    </div>
                    <div class="form-group">
                        <label for="tags">Tag Name:</label>
                        <input type="text" class="form-control" id="tags" name="tags[0][name]">
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status">
                            <option value="available">Available</option>
                            <option value="pending">Pending</option>
                            <option value="sold">Sold</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-primary">Dodaj</button>
            </div>
            </form>
        </div>
    </div>
</div>
