<div class="modal fade" id="likesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-2xl p-3 p-md-4" style="border:0">

      {{-- header --}}
      <div class="d-flex align-items-center mb-2">
        <h5 class="m-0 fw-bold">Likes <span class="text-muted" id="likesCount" style="font-weight:500">(0)</span></h5>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      {{-- list card --}}
      <div class="bg-white rounded-3 shadow-sm p-0" style="overflow:hidden">
        <div id="likesList" class="likes-list">
          {{-- diisi via JS --}}
          <div class="text-center text-muted py-4">Loading…</div>
        </div>
      </div>

    </div>
  </div>
</div>
