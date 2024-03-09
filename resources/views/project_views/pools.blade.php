@extends('layouts.general')

@section('pools')
    <div class="container mt-5">
        <h2>Gestión de Pools</h2>

        <!-- Modal para agregar liquidez -->
        <div class="modal fade" id="addLiquidityModal" tabindex="-1" aria-labelledby="addLiquidityModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLiquidityModalLabel">Agregar Liquidez</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('addLiquidity') }}" method="post">
                            @csrf
                            {{-- ! <!-- TENGO QUE AÑADIR CAMPOS --> --}}
                            <button type="submit" class="btn btn-primary">Agregar Liquidez</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para retirar liquidez -->
        <div class="modal fade" id="removeLiquidityModal" tabindex="-1" aria-labelledby="removeLiquidityModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="removeLiquidityModalLabel">Retirar Liquidez</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('removeLiquidity') }}" method="post">
                            @csrf
                            {{-- ! <!-- TENGO QUE AÑADIR CAMPOS --> --}}
                            <button type="submit" class="btn btn-primary">Retirar Liquidez</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear pool -->
        <div class="modal fade" id="createPoolModal" tabindex="-1" aria-labelledby="createPoolModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createPoolModalLabel">Crear Pool</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('createPool') }}" method="post">
                            @csrf
                            {{-- ! <!-- TENGO QUE AÑADIR CAMPOS --> --}}
                            <button type="submit" class="btn btn-primary">Crear Pool</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para eliminar pool -->
        <div class="modal fade" id="deletePoolModal" tabindex="-1" aria-labelledby="deletePoolModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deletePoolModalLabel">Eliminar Pool</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('deletePool') }}" method="post">
                            @csrf
                            {{-- ! <!-- TENGO QUE AÑADIR CAMPOS --> --}}
                            <button type="submit" class="btn btn-primary">Eliminar Pool</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones para abrir los modales -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addLiquidityModal">
            Agregar Liquidez
        </button>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#removeLiquidityModal">
            Retirar Liquidez
        </button>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPoolModal">
            Crear Pool
        </button>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#deletePoolModal">
            Eliminar Pool
        </button>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
@endpush
