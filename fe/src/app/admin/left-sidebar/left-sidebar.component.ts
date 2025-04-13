import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { LogoutService } from '../logout/logout.service';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-sidebar',
  standalone: true,
  templateUrl: './left-sidebar.component.html',
  styleUrls: ['./left-sidebar.component.css'],
  imports: [CommonModule],
  providers: [LogoutService],
})
export class SidebarComponent implements OnInit {
  activePage: string = '';

  constructor(
    public router: Router,
    private logoutService: LogoutService,
    private http: HttpClient
  ) {}

  ngOnInit(): void {
    // Inisialisasi jika perlu
  }

  onNavigate(route: string): void {
    this.activePage = route.split('/').pop() || '';
    this.router.navigate([route]).then(() => {
      console.log(`Navigasi ke halaman ${this.activePage}`);
      window.location.reload(); // Refresh halaman
    });
  }

  onLogout(): void {
    if (confirm('Yakin ingin logout?')) {
      this.logoutService.logout();
    }
  }
}
