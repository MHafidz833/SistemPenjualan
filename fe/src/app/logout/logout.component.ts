import { Component } from '@angular/core';
import { LogoutService } from './logout.service';

@Component({
  selector: 'app-logout',
  imports: [],
  templateUrl: './logout.component.html',
  styleUrl: './logout.component.css'
})
export class LogoutComponent {
  constructor(private logoutService: LogoutService) { }

  logout() {
    this.logoutService.logout();
  }
}
