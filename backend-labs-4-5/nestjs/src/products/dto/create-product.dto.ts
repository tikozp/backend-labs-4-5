import { ApiProperty } from '@nestjs/swagger';

export class CreateProductDto {
  @ApiProperty({ example: 'Laptop', description: 'Product name' })
  name: string;

  @ApiProperty({ example: 999.99, description: 'Product price' })
  price: number;

  @ApiProperty({ example: 'Powerful laptop', description: 'Product description' })
  description?: string;

  @ApiProperty({ example: 1, description: 'Category ID' })
  categoryId: number;
}