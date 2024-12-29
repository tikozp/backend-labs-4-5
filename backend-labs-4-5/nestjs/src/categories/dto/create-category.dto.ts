import { ApiProperty } from '@nestjs/swagger';

export class CreateCategoryDto {
  @ApiProperty({ example: 'Electronics', description: 'Category name' })
  name: string;

  @ApiProperty({ example: 'Electronic devices and gadgets', description: 'Category description' })
  description?: string;
}
