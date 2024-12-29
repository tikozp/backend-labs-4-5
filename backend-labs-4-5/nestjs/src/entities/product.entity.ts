import { Entity, Column, PrimaryGeneratedColumn, ManyToOne } from 'typeorm';
import { ApiProperty } from '@nestjs/swagger';
import { Category } from './category.entity';

@Entity('products')
export class Product {
  @ApiProperty()
  @PrimaryGeneratedColumn()
  id: number;

  @ApiProperty()
  @Column()
  name: string;

  @ApiProperty()
  @Column({ type: 'decimal', precision: 10, scale: 2 })
  price: number;

  @ApiProperty()
  @Column({ nullable: true })
  description: string;

  @ManyToOne(() => Category, category => category.products)
  category: Category;
}