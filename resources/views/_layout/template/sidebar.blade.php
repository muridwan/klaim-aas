<aside class="main-sidebar elevation-4 sidebar-light-success">
  <!-- Brand Logo -->
  <a href="{{ url('/') }}" class="brand-link bg-success">
    <img src="{{ asset('adminLTE') }}/dist/img/sidebar/PT_AAS.png" alt="AdminLTE Logo"
      class="brand-image bg-white img-circle elevation-3 " style="opacity: 1">
    <span class="brand-text font-weight-bold ml-1" style="letter-spacing: 0.6px">KLAIM CORE</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('adminLTE') }}/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ get_words(session('user_data')['name'], 3) }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="#" class="nav-link my-1 bg-gradient-secondary font-weight-bold text-white">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/dashboard.png') }}" alt="dashboard">
            <p style="letter-spacing: 0.2px">
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-header font-weight-bold mt-2" style="">DATA MASTER</li>
        <li class="nav-item border-bottom border-top">
          <a href="{{ route('institutions') }}"
            class="nav-link my-1 {{ $menu == 'sumber bisnis' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/acquisition.png') }}"
              alt="sumber bisnis">
            <p>
              Sumber Bisnis
            </p>
          </a>
        </li>
        <li class="nav-item border-bottom">
          <a href="{{ route('offices') }}"
            class="nav-link my-1 {{ $menu == 'kantor operasional' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/building.png') }}"
              alt="kantor operasional">
            <p>
              Kantor Operasional
            </p>
          </a>
        </li>
        <li class="nav-item border-bottom">
          <a href="{{ route('outlets') }}"
            class="nav-link my-1 {{ $menu == 'outlet' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/shop.png') }}" alt="shop">
            <p>
              Gerai
            </p>
          </a>
        </li>
        <li class="nav-item border-bottom">
          <a href="{{ route('files') }}"
            class="nav-link my-1 {{ $menu == 'risiko' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/compliant.png') }}"
              alt="Dokumen Pendukung">
            <p>
              Dokumen
            </p>
          </a>
        </li>
        <li class="nav-item border-bottom">
          <a href="{{ route('businesses') }}"
            class="nav-link my-1 {{ $menu == 'kelas bisnis' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/market-share.png') }}"
              alt="kelas bisnis">
            <p>
              Kelas Bisnis
            </p>
          </a>
        </li>

        <li class="nav-item border-bottom">
          <a href="{{ route('roles') }}"
            class="nav-link my-1 {{ $menu == 'pengguna' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/teamwork.png') }}" alt="pengguna">
            <p>
              Pengguna
            </p>
          </a>
        </li>



        <li class="nav-header font-weight-bold mt-2" style="">DATA KLAIM</li>
        <li class="nav-item border-bottom border-top">
          <a href="{{ route('claims', ['status' => 'submission']) }}"
            class="nav-link my-1 {{ $menu == 'pengajuan' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="24" src="{{ asset('AdminLTE/dist/img/sidebar/send.png') }}" alt="pengajuan klaim">
            <p>
              Pengajuan
              {{-- <span class="right badge badge-warning">5</span> --}}
            </p>
          </a>
        </li>
        <li class="nav-item border-bottom">
          <a href="{{ route('claims', ['status' => 'review']) }}"
            class="nav-link my-1 {{ $menu == 'peninjauan' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/folder.png') }}"
              alt="peninjauan klaim">
            <p>
              Peninjauan
            </p>
          </a>
        </li>
        <li class="nav-item border-bottom">
          <a href="{{ route('claims', ['status' => 'decision']) }}"
            class="nav-link my-1 {{ $menu == 'keputusan' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/choice.png') }}"
              alt="keputusan klaim">
            <p>
              Persetujuan
            </p>
          </a>
        </li>
        <li class="nav-item border-bottom">
          <a href="{{ route('claims', ['status' => 'payment']) }}"
            class="nav-link my-1 {{ $menu == 'pembayaran' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/claims.png') }}" alt="klaim diproses">
            <p>
              Pembayaran
            </p>
          </a>
        </li>
        <li class="nav-item border-bottom">
          <a href="{{ route('claims', ['status' => 'history']) }}"
            class="nav-link my-1 {{ $menu == 'riwayat' ? 'bg-gradient-success font-weight-bold text-white' : '' }}">
            <img class="mr-2" width="25" src="{{ asset('AdminLTE/dist/img/sidebar/claim.png') }}" alt="klaim diproses">
            <p>
              Riwayat
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>