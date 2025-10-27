@extends('templates.app')

@section('content')
    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.index') }}" class="btn btn-primary">Kembali</a>
        </div>

        <h3 class="my-3">Data Promo</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
        <table class="table table-bordered">
            <tr>
               <th>#</th>
                <th>Kode Promo</th>
                <th>Total Potongan</th>
                <th>Aksi</th>
            </tr>
                      @foreach ($promoTrash as $key => $item)
                <tr class="text-center">
                      <td>{{ $key + 1 }}</td>
                    <td>{{ $item['promo_code'] }}</td>
                    <td>
                        @if ($item['type'] == 'rupiah')
                            <small class="badge badge-primary">Rp. {{ number_format($item['discount'], 0, ',', '.') }}</small>
                        @else
                            <small class="badge badge-primary">{{ $item['discount'] }} %</small>
                        @endif
                            <form action="{{ route('staff.promos.restore', $item->id) }}" method="POST">
                                @csrf
                                  @method('PATCH')
                            <button class="btn btn-success mx-2">Kembalikan</button>
                        </form>
                          <form action="{{ route('staff.promos.delete_permanent', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger mx-2">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection

