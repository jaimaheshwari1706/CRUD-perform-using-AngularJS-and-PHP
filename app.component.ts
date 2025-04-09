import { Component} from '@angular/core';
import { Router } from '@angular/router';
import { UserserviceService } from './userservice.service';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent   {
  name:any = '';
  addBtn(): any {

  }

  constructor(private service:UserserviceService) { }

  ngOnInit(): void {

  }

}
