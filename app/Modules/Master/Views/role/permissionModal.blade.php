<form id="form" class="form" action="{{ $form_act }}" method="POST">
  @csrf
  <div class="row mb-2">
    <label class="col-sm-4 col-form-label">Role Name</label>
    <div class="col-sm-8">
      <input type="text" class="form-control-plaintext" value="{{ $main['role_nm'] }}" readonly>
    </div>
  </div>

  <div class="permission-tree border rounded p-3 bg-light mb-4" style="max-height: 400px; overflow-y: auto;">
    <ul class="list-unstyled">
      @foreach($all_nav as $menu)
        <li>
          <div class="form-check">
            <input class="form-check-input parent-checkbox" type="checkbox" name="nav_ids[]" value="{{ $menu['nav_id'] }}" id="nav_{{ $menu['nav_id'] }}" @checked(in_array($menu['nav_id'], $role_nav_ids))>
            <label class="form-check-label font-weight-600" for="nav_{{ $menu['nav_id'] }}">
              <i class="{{ $menu['nav_icon'] }} me-1 text-primary"></i> {{ $menu['nav_nm'] }}
            </label>
          </div>
          @if(!empty($menu['child']))
            <ul class="list-unstyled ms-4 mt-2">
              @foreach($menu['child'] as $child)
                <li>
                  <div class="form-check">
                    <input class="form-check-input child-checkbox" type="checkbox" name="nav_ids[]" value="{{ $child['nav_id'] }}" id="nav_{{ $child['nav_id'] }}" @checked(in_array($child['nav_id'], $role_nav_ids))>
                    <label class="form-check-label" for="nav_{{ $child['nav_id'] }}">
                      {{ $child['nav_nm'] }}
                    </label>
                  </div>
                  @if(!empty($child['child']))
                    <ul class="list-unstyled ms-4 mt-1">
                      @foreach($child['child'] as $grandchild)
                        <li>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="nav_ids[]" value="{{ $grandchild['nav_id'] }}" id="nav_{{ $grandchild['nav_id'] }}" @checked(in_array($grandchild['nav_id'], $role_nav_ids))>
                            <label class="form-check-label" for="nav_{{ $grandchild['nav_id'] }}">
                              {{ $grandchild['nav_nm'] }}
                            </label>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  @endif
                </li>
              @endforeach
            </ul>
          @endif
        </li>
        @if(!$loop->last) <hr class="my-2 opacity-25"> @endif
      @endforeach
    </ul>
  </div>

  <div class="row border-top pt-3">
    <div class="col-sm-12 d-flex justify-content-end gap-2">
      <button type="submit" class="btn btn-primary btn-submit px-4" onclick="fsSave(event)">
        <i class="fas fa-save me-2"></i>Simpan Permission
      </button>
      <button type="button" class="btn btn-outline-secondary btn-cancel px-4" onclick="fsModalHide(event, 0)">
        <i class="fas fa-times me-2"></i>Batal
      </button>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    // Optional: Auto check children when parent is checked
    // and auto check parent when child is checked
    $('.parent-checkbox').on('change', function() {
        $(this).closest('li').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    });
  });
</script>
