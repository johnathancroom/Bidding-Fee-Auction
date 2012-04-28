class CreateListings < ActiveRecord::Migration
  def change
    create_table :listings do |t|
      t.integer :id
      t.string :name
      t.integer :retail

      t.timestamps
    end
    add_index :listings, :id, :unique => true
  end
end
