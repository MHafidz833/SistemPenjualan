import { Component, OnInit, NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import axios from 'axios';
import { LogoutService } from '../logout/logout.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css'],
  imports: [
    CommonModule
  ],
})
export class NavbarComponent implements OnInit {
  isLoggedIn: boolean = false;
  username: string | null = null;
  loginStatus: string | null = null;
  pelanggan: any;
  isNavbarOpen = false; // Untuk mengontrol buka/tutup hamburger menu

  constructor(public router: Router, private logoutService: LogoutService) {}

  ngOnInit(): void {
    this.checkLoginStatus();
  }

  checkLoginStatus(): void {
    axios.get('http://localhost/mahiahijab/api/auth/login.php')
      .then(response => {
        this.loginStatus = localStorage.getItem('isLogin');
        this.pelanggan = localStorage.getItem('user');
        if (this.loginStatus == 'true') {
          this.isLoggedIn = true;
          this.username = response.data.user;  // Disesuaikan dengan data yang diterima dari API
        } else {
          this.isLoggedIn = false;
        }
      })
      .catch(error => {
        console.error('Error checking login status', error);
        this.isLoggedIn = false;
      });
  }

  toggleNavbar(): void {
    this.isNavbarOpen = !this.isNavbarOpen; // Toggle status hamburger menu
  }

  logout(): void {
    this.logoutService.logout();
    this.isNavbarOpen = false; // Tutup navbar saat logout
  }
}
