import { Component, OnInit } from '@angular/core';
import { UserserviceService } from '../userservice.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-table',
  templateUrl: './table.component.html',
  styleUrls: ['./table.component.css']
})
export class TableComponent implements OnInit {

  constructor(private service: UserserviceService
    , private route: ActivatedRoute
  ) { }

  ngOnInit(): void {
    this.getdata();

  }
tables: any=[]

getdata(){
  this.service.gettable().subscribe(res=>{
    this.tables = res;
    console.log('Fetched Data:', this.tables);
  })
}

deleteBtn(id:any){
  this.service.delete(id).subscribe(res => {
    console.log('Deleted Data:', res);
    this.getdata();
  });
}
}
